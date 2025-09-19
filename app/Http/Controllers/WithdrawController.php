<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Config;
use App\Models\GameHistory;
use App\Models\Gateway;
use App\Models\Withdraw;
use App\Models\WithdrawAffiliate;
use Illuminate\Http\Request;
use App\Traits\Gateways\{BspayApi, EzzebankApi, PrimepagApi, CitrexpayApi};
use App\Traits\SendMail;
use App\Traits\SendWebhook;
use Illuminate\Support\Str;

class WithdrawController extends Controller
{
    use BspayApi, EzzebankApi, PrimepagApi, CitrexpayApi, SendMail, SendWebhook;

    public function withdraw(Request $request)
    {
        try {
            $config = Config::first();
            $user = auth()->user();
            $totalrollover = GameHistory::where('user_id', $user->id)->sum('amount');
            $amount = $request->amount / 100;

            if ($user->user_demo) {
                return response()->json([
                    'success' => false,
                    'message' => __('back.gateway.withdraw-demo')
                ], 400);
            }

            if (!isset($user->cpf)) {
                return response()->json([
                    'success' => false,
                    'message' => __('back.gateway.no-registered-cpf')
                ], 400);
            }

            if ($user->wallet < $amount) {
                return response()->json([
                    'success' => false,
                    'message' => __('back.gateway.withdraw-no-funds')
                ], 400);
            }

            if ($amount < $config->minimosaque) {
                return response()->json([
                    'success' => false,
                    'message' => __('back.gateway.withdraw-min-value') . $config->minimosaque
                ], 400);
            }

            if ($amount > $config->maxsaque) {
                return response()->json([
                    'success' => false,
                    'message' => __('back.gateway.withdraw-max-value') . $config->maxsaque
                ], 400);
            }
            
            if ($config->base_rollover > 0 && $totalrollover >= $user->rollover) {
                return response()->json([
                    'success' => false,
                    'message' => __('back.gateway.withdraw-rollover')
                ], 400);
            }

            $document = $user->cpf;
            $transactionId = Str::uuid();

            // 📌 Calcular taxa
            if ($config->tax_active) {
                $amount_tax = $amount * (100 - $config->tax_withdraw) / 100;
                $tax = $config->tax_withdraw;
            } else {
                $amount_tax = $amount;
                $tax = 0;
            }

            $withdrawInfo = (object) [
                'user' => $user,
                'email' => $user->email,
                'amount' => $amount,               // Valor cheio
                'amount_tax' => $amount_tax,       // Valor com desconto
                'tax' => $tax,                     // % da taxa
                'document' => $document,
                'pix' => $document,
                'status' => 16,
                'transactionId' => $transactionId
            ];

            // 📌 Verifica limite de saques nas últimas 24h
            $last24h = Carbon::now('UTC')->subDay();
            $recentWithdraws = Withdraw::where('date', '>=', $last24h)
                ->where('email', '=', $user->email)
                ->whereNotIn('status', [420]) // só conta os que não foram negados
                ->count();

            if ($recentWithdraws >= $config->daily_limit_whitdrawal) {
                return response()->json([
                    'success' => false,
                    'message' => __('back.gateway.withdraw-daily-limit')
                ], 400);
            }

            // 📌 AUTO-SAQUE
            if ($config->auto_withdraw && $amount <= $config->maxauto_withdraw) {
                $dailyWithdrawPaid = Withdraw::where('status', 1)
                    ->where('date', '>=', Carbon::now()->subDay())
                    ->sum('amount');

                if ($dailyWithdrawPaid + $amount_tax < $config->daily_max_auto_withdraw) {
                    $gatewayActive = Gateway::where('is_active', true)
                        ->orderBy('position', 'asc')
                        ->first();

                    if ($gatewayActive) {
                        $methodName = 'withdraw' . $gatewayActive->gateway_name;

                        if (method_exists($this, $methodName)) {
                            $this->{$methodName}($withdrawInfo);
                        }
                    }
                } else {
                    $this->createWithdraw($withdrawInfo);
                }
            } else {
                $this->createWithdraw($withdrawInfo);
            }

            return response()->json([
                'success' => true,
                'message' => __('back.gateway.withdraw-success')
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('back.gateway.exception')
            ], 400);
        }
    }

    public function createWithdraw($withdrawInfo)
    {
        $user = $withdrawInfo->user;

        // 💰 Desconta valor total da carteira
        $user->decrement('wallet', $withdrawInfo->amount);

        // 💾 Registra valor líquido e taxa
        Withdraw::create([
            'email' => $user->email,
            'amount' => $withdrawInfo->amount_tax,  // valor com desconto
            'pix' => $withdrawInfo->pix,
            'status' => 16,
            'transactionId' => $withdrawInfo->transactionId,
            'tax_withdraw' => $withdrawInfo->tax  // grava a taxa %
        ]);

        $this->withdrawHook($withdrawInfo);
    }

    public function withdrawAffiliate(Request $request)
    {
        try {
            $config = Config::first();
            $user = auth()->user();
            $amount = $request->amount / 100;

            if (!isset($user->cpf)) {
                return response()->json([
                    'success' => false,
                    'message' => __('back.gateway.no-registered-cpf')
                ], 400);
            }

            if ($user->referRewards < $amount) {
                return response()->json([
                    'success' => false,
                    'message' => __('back.gateway.withdraw-no-funds')
                ], 400);
            }

            if ($amount < $config->w_min_affiliate) {
                return response()->json([
                    'success' => false,
                    'message' => __('back.gateway.withdraw-min-value') . $config->w_min_affiliate
                ], 400);
            }

            if ($amount > $config->w_max_affiliate) {
                return response()->json([
                    'success' => false,
                    'message' => __('back.gateway.withdraw-min-value') . $config->w_max_affiliate
                ], 400);
            }

            $last24h = Carbon::now()->subDay();
            $recentWithdraws = WithdrawAffiliate::where('date', '>=', $last24h)
                ->where('email', '=', $user->email)
                ->count();

            if ($recentWithdraws >= $config->dl_affiliate) {
                return response()->json([
                    'success' => false,
                    'message' => __('back.gateway.withdraw-daily-limit')
                ], 400);
            }

            $user->decrement('referRewards', $amount);

            WithdrawAffiliate::create([
                'email' => $user->email,
                'amount' => $amount,
                'pix' => $user->cpf,
                'status' => 16
            ]);

            return response()->json([
                'success' => true,
                'message' => __('back.gateway.withdraw-success')
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('back.gateway.withdraw-error')
            ], 400);
        }
    }

    public function listWithdrawals(Request $request)
    {
        $request->validate([
            'page' => ['sometimes', 'integer'],
            'per_page' => ['sometimes', 'integer', 'max:50'],
        ]);

        $withdraws = Withdraw::query()
            ->select('status', 'amount', 'date', 'description')
            ->where('email', auth()->user()->email)
            ->orderBy('date', 'DESC')
            ->paginate(
                page: $request->page,
                perPage: $request->per_page
            );

        foreach ($withdraws->items() as $withdraw) {
            $withdraw->status = match ($withdraw->status) {
                16 => 'pending',
                420 => 'denied',
                default => 'approved',
            };

            $withdraw->value = $withdraw->amount * 100;
            $withdraw->created_at = Carbon::parse($withdraw->date, "UTC")
                ->setTimezone('America/Sao_Paulo')
                ->toDateTimeString();

            $withdraw->status_detail = $withdraw->description;
        }

        return $withdraws;
    }
}

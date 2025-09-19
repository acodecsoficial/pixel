<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Config;
use App\Models\BetRiskLog;
use App\Models\GameHistory;
use Illuminate\Http\Request;
use App\Models\GameProvider;
use App\Traits\AffiliateRewards;

class ElloController extends Controller
{
    use AffiliateRewards;

    private $agent_code;
    private $agent_token;
    private $max_risk;
    private $manage_risk;

    public function __construct()
    {
        $this->max_risk = Config::first()->value('max_risk');
        $this->manage_risk = Config::first()->value('manage_risk');
        $elloCred = GameProvider::where('provider_name', 'ELLO')->first();

        $this->agent_code = $elloCred->agent_code;
        $this->agent_token = $elloCred->agent_token;
    }

    private function getUserBalance($user_code)
    {
        $user = User::find($user_code);

        if (!$user) {
            return response()->json([
                "status" => 0,
                "msg" => "User not found.",
            ], 400);
        }

        $wallet = $user->wallet + $user->wallet_bonus;

        return [
            "status" => 1,
            "user_balance" => $wallet
        ];
    }

    private function setTransaction($user_code, $bet_money, $win_money, $txn_id, $game_code, $provider_code)
    {
        $user = User::find($user_code);

        if (!$user) {
            return response()->json([
                "status" => 0,
                "msg" => "User not found.",
            ], 400);
        }

        $wallet = $user->wallet + $user->wallet_bonus;

        if ($bet_money > (float) $wallet) {
            return response()->json([
                "status" => 0,
                "user_balance" => 0,
                "msg" => "INSUFFICIENT_USER_FUNDS"
            ], 400);
        }

        $rollover = 0;
        if ($user->rollover > 0.0) {
            $rollover = $user->rollover - $bet_money;
        }
        if ($rollover < 0.0) {
            $rollover = 0;
        }

        if ($user->user_demo == 0 && $user->wallet_bonus > 0.0 && $rollover == 0.0) {
            // Verifica se o usuário tem saldo bônus e que possui igual ou mais que o valor da bet,
            // Verifica também se o usuário é jogador normal e que possui rollover zerado,
            // Apenas realiza a conversão de saldo bônus para real caso o usuário tenha rollover = 0

            $total = floatval($win_money);

            if ($user->wallet_bonus < $bet_money) {
                $remWallet = $bet_money - $user->wallet_bonus;

                $user->update(['wallet_bonus' => 0]);
                $user->decrement('wallet', $remWallet);

                $user->increment('wallet', $total);
                $user->update(['rollover' => $rollover]);
            } else {
                $user->increment('wallet', floatval($win_money));
                $user->decrement('wallet_bonus', floatval($bet_money));
                $user->update(['rollover' => $rollover]);
            }
        } else if ($user->user_demo == 1 || ($rollover > 0 && $user->wallet_bonus > 0)) {
            // Verifica se o usuário é demo ou possui rollover a ser batido,
            // Também verifica se usuário possui saldo bônus, pois caso não tenha, deverá bater rollover com saldo real,
            // Caso possua ou seja demo, apenas aumenta saldo bônus conforme a bet.

            $total = floatval($win_money) - floatval($bet_money);

            if ($user->wallet_bonus < $bet_money) {
                $remWallet = $bet_money - $user->wallet_bonus;

                $user->update(['wallet_bonus' => 0]);
                $user->decrement('wallet', $remWallet);

                $user->increment('wallet_bonus', $win_money);
                $user->update(['rollover' => $rollover]);
            } else {
                $user->increment('wallet_bonus', $total);
                $user->update(['rollover' => $rollover]);
            }
        } else {
            // Acontece caso o usuário apenas possua saldo real,
            // Caso usuário não tenha rollover a ser batido,
            // Caso usuário não seja demo,

            $total = floatval($win_money) - floatval($bet_money);

            if ($this->manage_risk) {
                $this->manageRisk($total, $user, $rollover, $provider_code, $game_code);
            } else {
                $user->increment('wallet', $total);
                $user->update(['rollover' => $rollover]);
            }

            $totalRev = $total * -1;

            $this->revFivers($user, $totalRev);
        }

        GameHistory::firstOrCreate([
            'user_id' => $user_code,
            'amount' => $bet_money,
            'provider' => $provider_code,
            'provider_tx_id' => $txn_id,
            'game' => $game_code,
            'action' => 'bet',
            'round_id' => $txn_id,
            'session_token' => $txn_id,
        ]);

        GameHistory::firstOrCreate([
            'user_id' => $user_code,
            'amount' => $win_money > 0 ? $win_money : $bet_money,
            'provider' => $provider_code,
            'provider_tx_id' => $txn_id,
            'game' => $game_code,
            'action' => $win_money > 0 ? 'win' : 'loss',
            'round_id' => $txn_id,
            'session_token' => $txn_id,
        ]);

        return [
            "status" => 1,
            "user_balance" => $wallet
        ];
    }

    public function webhookEllo(Request $request)
    {

        $agent_token = $request->agent_token;
        $agent_code = $request->agent_code;
        $user_code = $request->user_code;
        $method = $request->method;
        $game = $request->slot;

        if ($agent_code === $this->agent_code && $agent_token === $this->agent_token) {
            switch ($method) {
                case 'user_balance':
                    return $this->getUserBalance($user_code);

                case 'spin':
                    return $this->setTransaction($user_code,  $game['bet_money'], $game['win_money'], $game['txn_id'], $game['game_code'], 'PG Soft');
            }
        }

        return response()->json([
            "status" => 0,
            "user_balance" => 0,
            "msg" => "INTERNAL_ERROR"
        ], 400);
    }

    public function manageRisk($total, $user, $rollover, $provider_code, $game_code)
    {
        if ($total <= $this->max_risk) {
            $user->increment('wallet', $total);
            $user->update(['rollover' => $rollover]);
        } else {
            BetRiskLog::create([
                'email' => $user->email,
                'status' => 'pending',
                'amount' => $total,
                'provider' => $provider_code,
                'game' => $game_code
            ]);
        }
    }
}

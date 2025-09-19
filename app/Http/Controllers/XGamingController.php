<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Config;
use App\Models\BetRiskLog;
use App\Models\GameHistory;
use Illuminate\Http\Request;
use App\Models\GameProvider;
use App\Traits\AffiliateRewards;

class XGamingController extends Controller
{
    use AffiliateRewards;

    private $agent_name;
    private $agent_token;
    private $max_risk;
    private $manage_risk;

    public function __construct()
    {
        $this->max_risk = Config::first()->value('max_risk');
        $this->manage_risk = Config::first()->value('manage_risk');
        $xgCred = GameProvider::where('provider_name', 'XGAMING')->first();

        $this->agent_name = $xgCred->agent_code;
        $this->agent_token = $xgCred->agent_token;
    }

    private function getUserBalance($user_code)
    {
        $user = User::find($user_code);

        return [
            "username" => $user_code,
            "balance_amount" => $user->wallet,
            "verify_balance" => true
        ];
    }

    private function setTransaction($user_code, $bet_money, $win_money, $txn_id, $game_code, $provider_code, $method)
    {
        $user = User::find($user_code);

        if (!$user) {
            return response()->json([
                "success" => false,
                "message" => "User not found.",
            ], 400);
        }

        $wallet = $user->wallet + $user->wallet_bonus;

        if ($bet_money > (float) $wallet) {
            return response()->json([
                "verify_balance" => 0,
                "user_balance" => 0,
                "username" => $user_code,
            ], 400);
        }

        $rollover = 0;

        if ($user->rollover > 0.0) {
            $rollover = $user->rollover - floatval($bet_money);
        }

        if ($rollover < 0.0) {
            $rollover = 0;
        }

        // As funções da lógica a seguir foram feitas para que o afiliado tenha o ganho justo.
        // Levando em consideração que deve receber Revenue Share apenas de saldo real.

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

            $typeBet = $win_money > 0 ? "WIN" : "LOSS";

            if ($method == 'pg_bet_callback' && $win_money > 0) {
                $typeBet = "LOSS";
            }

            if ($method == 'ttl_win_callback') {
                $typeBet = "LOSS";
            }

            $this->revXGaming($user, $total, $typeBet);
        }

        if ($method == 'pg_bet_callback') {
            GameHistory::create([
                'user_id' => $user_code,
                'amount' => $bet_money,
                'provider' => $provider_code,
                'provider_tx_id' => $txn_id,
                'game' => $game_code,
                'action' => 'bet',
                'round_id' => $txn_id,
                'session_token' => $txn_id,
                'demo' => $user->user_demo
            ]);

            GameHistory::create([
                'user_id' => $user_code,
                'amount' => $win_money > 0 ? $win_money : $bet_money,
                'provider' => $provider_code,
                'provider_tx_id' => $txn_id,
                'game' => $game_code,
                'action' => $win_money > 0 ? 'win' : 'loss',
                'round_id' => $txn_id,
                'session_token' => $txn_id,
                'demo' => $user->user_demo
            ]);
        }

        if ($method == 'ttl_bet_callback') {
            GameHistory::create([
                'user_id' => $user_code,
                'amount' => $bet_money,
                'provider' => $provider_code,
                'provider_tx_id' => $txn_id,
                'game' => $game_code,
                'action' => 'bet',
                'round_id' => $txn_id,
                'session_token' => $txn_id,
                'demo' => $user->user_demo
            ]);
        }

        if ($method == 'ttl_win_callback') {
            GameHistory::create([
                'user_id' => $user_code,
                'amount' => $win_money > 0 ? $win_money : $bet_money,
                'provider' => $provider_code,
                'provider_tx_id' => $txn_id,
                'game' => $game_code,
                'action' => $win_money > 0 ? 'win' : 'loss',
                'round_id' => $txn_id,
                'session_token' => $txn_id,
                'demo' => $user->user_demo
            ]);
        }

        return [
            "username" => $user_code,
            "balance_amount" => $user->wallet,
            "verify_balance" => true
        ];
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

    public function webhookXGaming(Request $request)
    {
        $agent_name = $request->input('agent_name');
        $agent_token = $request->input('agent_token');
        $user_code = str_replace($this->agent_name, '', $request->input('username'));
        $method = $request->input('method');

        if ($request->input('provider')) {
            $provider_name = $request->input('provider');
        } else {
            $provider_name = 'EVOLUTION';
        }

        switch ($provider_name) {
            case 'evo':
                $provider_name = 'EVOLUTION';
                break;
            case 'spribe':
                $provider_name = 'Spribe';
                break;
            case 'pragmatic':
                $provider_name = 'Pragmatic Play';
                break;
            default:
                $provider_name = 'EVOLUTION';
                break;
        }

        if ($agent_name === $this->agent_name && $agent_token === $this->agent_token) {
            switch ($method) {
                case 'pg_wallet_check':
                    return $this->getUserBalance($user_code);

                case 'pg_bet_callback':
                    return $this->setTransaction($user_code, $request->bet_amount, $request->win_amount, "", $request->gameId, "PG Soft", $method);

                case 'ttl_bet_callback':
                    return $this->setTransaction($user_code, $request->bet_amount, $request->win_amount, "", $request->gameId, $provider_name, $method);

                case 'ttl_win_callback':
                    return $this->setTransaction($user_code, $request->bet_amount, $request->win_amount, "", $request->gameId, $provider_name, $method);
            }
        }

        return response()->json([
            "status" => 0,
            "user_balance" => 0,
            "msg" => "INTERNAL_ERROR"
        ], 400);
    }
}

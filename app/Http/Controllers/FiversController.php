<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GameProvider;
use App\Traits\AffiliateRewards;
use App\Models\GameHistory;
use App\Models\Config;
use App\Models\BetRiskLog;

class FiversController extends Controller
{
    use AffiliateRewards;

    private $agent_code;
    private $agent_token;
    private $agent_secret;
    private $max_risk;
    private $manage_risk;

    public function __construct()
    {
        $this->max_risk = Config::first()->value('max_risk');
        $this->manage_risk = Config::first()->value('manage_risk');
        $fiversCred = GameProvider::where('provider_name', 'FIVERS')->first();

        $this->agent_code = $fiversCred->agent_code;
        $this->agent_token = $fiversCred->agent_token;
        $this->agent_secret = $fiversCred->agent_secret;
    }

    private function getUserBalance($user_code)
    {
        $user = User::find($user_code);

        return [
            "status" => 1,
            "user_balance" => $user->wallet
        ];
    }

    private function setTransaction($user_code, $bet_money, $win_money, $txn_id, $game_code, $provider_code)
    {
        $user = User::find($user_code);

        if (!$user) {
            return response()->json([
                "success" => false,
                "message" => "User not found.",
            ], 400);
        }

        if ($bet_money > $user->wallet) {
            return response()->json([
                "status" => 0,
                "user_balance" => 0,
                "msg" => "INTERNAL_ERROR"
            ], 400);
        }

        $rollover = 0;

        if ($user->rollover > 0.0) {
            $rollover = $user->rollover - $bet_money;
        }

        if ($rollover < 0.0) {
            $rollover = 0;
        }

        $total = $win_money - $bet_money;

        // BET MANAGE RISK

        if ($this->manage_risk == true) {
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
        } else {
            $user->increment('wallet', $total);
            $user->update(['rollover' => $rollover]);
        }

        //

        $totalRev = $total * -1;

        $this->revFivers($user, $totalRev);

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
            "user_balance" => $user->wallet
        ];
    }

    public function webhookFivers(Request $request)
    {

        $agent_code = $request->agent_code;
        $agent_secret = $request->agent_secret;
        $user_code = $request->user_code;
        $game_type = $request->game_type;
        $method = $request->method;
        $game = $request->$game_type;

        if ($agent_code === $this->agent_code && $agent_secret === $this->agent_secret) {
            switch ($method) {

                case 'user_balance':
                    return $this->getUserBalance($user_code);

                case 'transaction':
                    return $this->setTransaction($user_code,  $game['bet_money'], $game['win_money'], $game['txn_id'], $game['game_code'], $game['provider_code']);
            }
        }
        return response()->json([
            "status" => 0,
            "user_balance" => 0,
            "msg" => "INTERNAL_ERROR"
        ], 400);
    }
}

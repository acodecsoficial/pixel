<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\GameHistory;
use App\Models\User;
use App\Models\GameProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SportController extends Controller
{
    protected string $hall_id;
    protected string $hall_key;

    public function __construct()
    {
        $sportbook = GameProvider::where('provider_name', 'TBS2API')->first();
        $this->hall_id = $sportbook->agent_code;
        $this->hall_key = $sportbook->agent_token;
    }

    public function launchSportbook(Request $request)
    {
        try {
            $user   = auth()->user();

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
                ->post('https://tbs2api.aslot.net/API/openGame/', [
                    "cmd" => "openGame",
                    "hall" => $this->hall_id,
                    "domain" => 'https://' . $_SERVER['SERVER_NAME'],
                    "exitUrl" => 'https://' . $_SERVER['SERVER_NAME'],
                    "language" => "br",
                    "key" => $this->hall_key,
                    "login" => $user->id,
                    "gameId" => "3000",
                    "demo" => 0
                ]);

            if ($response->successful()) {
                return [
                    'launchUrl' => $response['content']['game']['url']
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Provider error'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Api error'
            ];
        }
    }


    private function getUserBalance($user_code)
    {
        try {
            $user = User::find($user_code);

            return [
                "status" => "success",
                "error" => "",
                "login" => $user_code,
                "balance" => $user->wallet,
                "currency" => "BRL"
            ];
        } catch (\Exception $e) {
            return [
                "status" => "fail",
                "error" => "user_not_found"
            ];
        }
    }

    private function setTransaction($user_code, $bet_money, $win_money, $txn_id, $game_code, $provider_code, $action)
    {
        $user = User::find($user_code);

        if ($bet_money > $user->wallet) {
            return response()->json([
                "status" => "fail",
                "error" => "fail_balance"
            ], 400);
        }

        $rollover = 0;

        if ($user->rollover > 0.0) {
            $rollover = $user->rollover - floatval($bet_money);
        }

        if ($rollover < 0.0) {
            $rollover = 0;
        }

        $total = floatval($win_money) - floatval($bet_money);

        $user->increment('wallet', $total);
        $user->update(['rollover' => $rollover]);

        GameHistory::firstOrCreate([
            'user_id' => $user_code,
            'amount' => $bet_money,
            'provider' => $provider_code,
            'provider_tx_id' => $txn_id,
            'game' => $game_code,
            'action' => 'bet',
            'round_id' => $txn_id,
            'session_token' => $txn_id,
            'demo' => 0
        ]);

        $history = GameHistory::firstOrCreate([
            'user_id' => $user_code,
            'amount' => $win_money > 0 ? $win_money : $bet_money,
            'provider' => $provider_code,
            'provider_tx_id' => $txn_id,
            'game' => $game_code,
            'action' => $win_money > 0 ? 'win' : 'loss',
            'round_id' => $txn_id,
            'session_token' => $txn_id,
            'demo' => 0
        ]);

        return [
            "status" => "success",
            "error" => "",
            "login" => $user_code,
            "balance" => $user->wallet,
            "currency" => "BRL",
            "operationId" => $history->id * 100
        ];
    }

    public function webhook(Request $request)
    {
        $hall_id = $request->input('hall');
        $hall_key = $request->input('key');
        $user_code = $request->input('login');
        $method = $request->input('cmd');

        if ($hall_id === $this->hall_id && $hall_key === $this->hall_key) {
            switch ($method) {
                case 'getBalance':
                    return $this->getUserBalance($user_code);

                case 'writeBet':
                    return $this->setTransaction($user_code, $request->bet, $request->win, $request->tradeId, $request->gameId, "SportsBook", $request->betInfo);
            }
        }

        return response()->json([
            "status" => 0,
            "user_balance" => 0,
            "msg" => "INTERNAL_ERROR"
        ], 400);
    }
}

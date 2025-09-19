<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GameProvider;
use App\Models\GameHistory;
use App\Traits\AffiliateRewards;

class HypertechController extends Controller
{
    use AffiliateRewards;

    private $agent_token;

    public function __construct(){
        $hyperCred = GameProvider::where('provider_name', 'HYPERTECH')->first();

        $this->agent_token = $hyperCred->agent_token;
    }

    public function getUserBalance(Request $request) {
        $user = User::find($request->player_id);

        return [
            "balance" => $user->wallet,
        ];
    }

    public function registerBet(Request $request) {
        $data = $request->only([
            'user_id',
            'amount',
            'provider',
            'provider_tx_id',
            'game',
            'action',
            'round_id',
            'session_token',
        ]);

        $amount = floatval($data["amount"]);
        $user = User::find($data['user_id']);

        if ($amount > $user->wallet) {
            return response()->json( [
                "verify_balance" => 0,
                "user_balance" => 0,
                "username" => $user->email,
            ], 400);
        }

        $gameHistory = GameHistory::firstOrCreate($data);
        $old_balance = $user->wallet;

        $user->decrement('wallet', $amount);

        // $this->revAffiliate($user, $amount);

        return [
            "operator_tx_id" => $data["provider_tx_id"],
            "new_balance" => $user->wallet,
            "old_balance" => $old_balance,
            "user_id" => $data["user_id"],
            "currency" => "BRL",
            "provider" => "hypetech",
            "provider_tx_id" => $data["provider_tx_id"]
        ];
    }

    public function registerRewards(Request $request) {
        $data = $request->only([
            'user_id',
            'amount',
            'provider',
            'provider_tx_id',
            'game',
            'action',
            'round_id',
            'session_token',
        ]);

        $gameHistory = GameHistory::firstOrCreate($data);
        $user = User::find($data['user_id']);
        $amount = floatval($data["amount"]);

        $old_balance = $user->wallet;

        $user->increment('wallet', $amount);

        // $totalRev = $amount * -1;

        // $this->revAfiliado($user, $totalRev);

        return [
            "operator_tx_id" => $data["provider_tx_id"],
            "new_balance" => $user->wallet,
            "old_balance" => $old_balance,
            "user_id" => $data["user_id"],
            "currency" => "BRL",
            "provider" => "hypetech",
            "provider_tx_id" => $data["provider_tx_id"]
        ];
    }

    public function registerRollback(Request $request) {

        // Quando realizar o FIX aqui, descomentar as chamadas de revAfiliado acima

        // $data = $request->only([
        //     'user_id',
        //     'amount',
        //     'provider',
        //     'provider_tx_id',
        //     'game',
        //     'action',
        //     'round_id',
        //     'session_token',
        // ]);

        // $gameHistory = GameHistory::firstOrCreate($data);
        // $user = User::find($data['user_id']);
        // $amount = floatval($data->amount);

        // $old_balance = $user->wallet;

        // $user->increment('wallet', $amount);

        // $totalRev = $amount * -1;

        // $this->revAfiliado($user, $totalRev);


        return [
           //"operator_tx_id" => $data->provider_tx_id,
           //"new_balance" => $user->wallet,
           //"old_balance" => $old_balance,
           //"user_id" => $data->user_id,
           //"currency" => "BRL",
           //"provider" => "hypetech",
           //"provider_tx_id" => $data->provider_tx_id
        ];
    }

}

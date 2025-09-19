<?php

namespace App\Traits\Providers;

use App\Models\GameProvider;
use App\Models\GameToken;
use Illuminate\Support\Facades\Http;

trait XGamingProvider
{
    public function launchXgaming ($gameCode, $providerCode): string
    {
        $distributor = GameProvider::where('provider_name', 'XGAMING')->firstOrFail();
        $user = auth()->user();
        $wallet = $user->wallet;
        $provider_name = strtolower($providerCode);

        if($providerCode == 'PRAGMATIC PLAY'){
            $provider_name = 'pragmatic';
        }

        if($providerCode == 'PGSOFT'){
            $provider_name = 'pg';
            $wallet += $user->wallet_bonus;

            if($user->user_demo == true){
                $wallet = $user->wallet_bonus;
            }
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])
        ->post('https://xgamingprovider.tech/' . $provider_name . '/creategame/xgaming', [
            "gameId" => $gameCode,
            "requestAgent" => $distributor->agent_code,
            "requestAgentToken" => $distributor->agent_token,
            "userName" => $distributor->agent_code . $user->id,
            "userDemo" => $user->user_demo,
            "userBalance" => $wallet,
        ]);

        if ($response->failed() || !isset($response['data'])) {
            return response()->json([
                'error' => 'Could not create game url'
            ], 400);
        }

        return $response['data']['GameUrl'];
    }
}

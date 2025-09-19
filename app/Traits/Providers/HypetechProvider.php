<?php

namespace App\Traits\Providers;

use App\Models\GameProvider;
use App\Models\GamesApi;
use Illuminate\Support\Facades\Http;

trait HypetechProvider
{
    public function launchHypetech ($gameCode, $providerCode): string
    {
        $distributor = GameProvider::where('provider_name', 'HYPERTECH')->firstOrFail();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $distributor->agent_token,
        ])
            ->post('https://api.hypetech.games/games/request-access', [
                "game" => $gameCode,
                "user_id" => auth()->user()->id . "",
                "balance" => auth()->user()->wallet . "",
                "currency" => "BRL",
                "username" => auth()->user()->email,
                "lang" => "pt",
            ]);

        if ($response->failed() || !isset($response['game_url'])) {
            return response()->json([
                'error' => 'Could not create game url'
            ], 400);
        }

        return $response['game_url'];
    }
}

<?php

namespace App\Traits\Providers;

use App\Models\GameProvider;
use Illuminate\Support\Facades\Http;


trait ElloProvider
{
    public function launchEllo($game): string
    {
        $user = auth()->user();
        $gameCode = (string) str(request()->slug)->after('/');
        $distributor = GameProvider::where('provider_name', 'ELLO')->firstOrFail();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://api.elloservice.com/web-api/game_launch', [
            "domain" => $distributor->agent_secret,
            "agent_code" => $distributor->agent_code,
            "agent_token" => $distributor->agent_token,
            "user_code" => $user->id . "",
            "game_code" => $gameCode,
        ]);

        if ($response->failed() || !isset($response['url'])) {
            return response()->json([
                'status' => $response->status(),
                'body' => $response->body()
            ], 400);
        }

        return $response['url'];
    }
}

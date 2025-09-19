<?php

namespace App\Traits\Providers;

use App\Models\GameProvider;
use App\Models\GameToken;
use Illuminate\Support\Facades\Http;

trait TLTProvider
{
    public function launchTlt ($gameCode, $providerCode): string
    {
        $distributor = GameProvider::where('provider_name', 'TLT')->firstOrFail();

        $token  = $this->generateUniqueToken();
        GameToken::create([ 'user_id' => auth()->user()->id, 'token' => $token ]);

        $operator_id = $distributor->agent_code;
        $currency    = "BRL";
        $language    = "pt";
        $home_url    = "https://" . $_SERVER['SERVER_NAME'] . ".bet/";
        $launchUrl   = str_contains($providerCode, "PRAGMATIC")
            ? "https://run.games378.com"
            : "https://launch.timelesstech.org";

        // Construct the URL
        return "$launchUrl/?operator_id=$operator_id&mode=real&game_id=$gameCode&token=$token&currency=$currency&language=$language&home_url=$home_url";
    }

    private function generateUniqueToken()
    {
        $token = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', 25)), 0, 25);

        // Check if the token already exists, if so, generate a new one
        while (GameToken::where('token', $token)->exists()) {
            $token = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', 25)), 0, 25);
        }

        return $token;
    }
}

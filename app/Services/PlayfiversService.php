<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PlayfiversService
{
    public function __construct(
        private string $base   = '',
        private string $agent  = '',
        private string $secret = '',
        private string $lang   = 'pt',
    ) {
        $this->base   = config('playfivers.base_url', 'https://api.playfivers.com');
        $this->agent  = config('playfivers.agent_token', '');
        $this->secret = config('playfivers.secret_key', '');
        $this->lang   = config('playfivers.lang', 'pt');
    }

    private function client()
    {
        // Em produção, pode remover o withoutVerifying() se tiver CA certs ok
        return Http::withoutVerifying()
            ->withHeaders(['Content-Type' => 'application/json']);
    }

    public function launchGame(
        string $userCode,
        string $gameCode,
        bool $gameOriginal,
        float $userBalance,
        ?string $lang = null
    ): array {
        $payload = [
            'agentToken'    => $this->agent,
            'secretKey'     => $this->secret,
            'user_code'     => $userCode,
            'game_code'     => $gameCode,
            'game_original' => $gameOriginal,
            'user_balance'  => $userBalance,
            'lang'          => $lang ?: $this->lang,
        ];

        $res = $this->client()->post("{$this->base}/api/v2/game_launch", $payload);
        return $res->json() ?? [];
    }
}

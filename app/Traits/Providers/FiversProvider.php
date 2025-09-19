<?php

namespace App\Traits\Providers;

use App\Models\GameProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait FiversProvider
{
    /**
     * Lança jogo no PlayFivers e retorna a launch_url (para o iframe).
     * Requer um registro em game_providers com provider_name='FIVERS'
     * e colunas agent_token (token) e agent_secret (secret).
     */
    public function launchFivers($game): string
    {
        // slug no formato "PROVIDER/GAMECODE" vem de ?slug=PROVIDER/GAMECODE
        $slug = (string) request()->input('slug', request()->slug);
        $providerCode = (string) str($slug)->before('/');           // ex: Pragmatic Play
        $gameCodeFromSlug = (string) str($slug)->after('/');        // ex: vswaysbook

        // 1) Credenciais do PlayFivers no DB
        $distributor = GameProvider::where('provider_name', 'FIVERS')->first();
        if (!$distributor || !$distributor->agent_token || !$distributor->agent_secret) {
            throw new \RuntimeException('FIVERS: credenciais ausentes em game_providers (agent_token/agent_secret).');
        }
        $agentToken = $distributor->agent_token;
        $secretKey  = $distributor->agent_secret;

        // 2) Usuário e saldo (ajuste conforme sua base)
        $user = auth()->user();
        $userCode    = $user->email ?? ('guest-'.request()->ip());
        $userBalance = (float) ($user->wallet_balance ?? 0.0);

        // 3) Código do jogo: prefira o que está salvo em DB (game_id), caindo para o slug
        $gameCode = $game->game_id ?: $gameCodeFromSlug;

        // 4) Original/lingua/base
        $gameOriginal = (bool) ($game->is_original ?? false);
        $lang = config('playfivers.lang', 'pt');
        $base = config('playfivers.base_url', 'https://api.playfivers.com');

        // 5) Requisição
        $payload = [
            'agentToken'    => $agentToken,
            'secretKey'     => $secretKey,
            'user_code'     => $userCode,
            'game_code'     => $gameCode,
            'game_original' => $gameOriginal,
            'user_balance'  => $userBalance,
            'lang'          => $lang,
        ];

        // Em DEV, ignore SSL; em produção, troque para verificação do cacert.pem
        $res = Http::withoutVerifying()
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($base . '/api/v2/game_launch', $payload);

        if ($res->failed()) {
            Log::error('FIVERS launch HTTP failed', ['status' => $res->status(), 'body' => $res->body()]);
            throw new \RuntimeException('FIVERS: falha HTTP ao chamar /game_launch');
        }

        $json = $res->json();
        if (!($json['status'] ?? false)) {
            $msg = $json['msg'] ?? 'unknown_error';
            Log::warning('FIVERS launch error', ['msg' => $msg, 'payload' => $payload]);
            throw new \RuntimeException('FIVERS: '.$msg);
        }

        $launch = $json['launch_url'] ?? null;
        if (!$launch) {
            Log::warning('FIVERS launch_url ausente', ['json' => $json]);
            throw new \RuntimeException('FIVERS: launch_url ausente na resposta');
        }

        return (string) $launch;
    }
}

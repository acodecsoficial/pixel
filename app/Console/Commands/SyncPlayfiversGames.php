<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PlayfiversService;
use App\Models\GamesApi;
use Illuminate\Support\Str;

class SyncPlayfiversGames extends Command
{
    protected $signature   = 'playfivers:sync-games {--provider=}';
    protected $description = 'Sincroniza lista de jogos do PlayFivers na tabela games_api';

    public function handle(PlayfiversService $pf): int
    {
        $providerOpt = $this->option('provider');

        $this->info('Baixando jogos do PlayFivers' . ($providerOpt ? " (provider={$providerOpt})" : '') . '...');

        // Busca lista de jogos (sem filtro por provider para abrangência).
        // Se quiser filtrar por provider nome/ID, altere o PlayfiversService->games para aceitar string|int.
        $resp = $pf->games(); 
        $list = $resp['data'] ?? [];

        $count = 0;

        foreach ($list as $g) {
            // ---- provider (string) ----
            $providerName = $g['provider'] ?? ($g['provider_name'] ?? null);
            if (is_array($providerName)) {
                // tentativas comuns
                $providerName = $providerName['name'] ?? ($providerName['provider_name'] ?? reset($providerName));
            }
            $providerName = (string) $providerName;

            // ---- game code (string) ----
            $gameCode = $g['game_code'] ?? ($g['code'] ?? ($g['id'] ?? null));
            if (is_array($gameCode)) {
                $gameCode = $gameCode['code'] ?? reset($gameCode);
            }
            $gameCode = $gameCode !== null ? (string) $gameCode : '';

            // ---- name (string) ----
            $name = $g['name'] ?? ($g['title'] ?? '');
            if (is_array($name)) {
                // alguns retornam por locale: ['en' => '...', 'pt' => '...']
                $name = $name['pt'] ?? $name['en'] ?? reset($name);
            }
            $name = (string) $name;

            // ---- image (string) ----
            $image = $g['image_url'] ?? ($g['image'] ?? '');
            if (is_array($image)) {
                $image = $image['url'] ?? reset($image) ?? '';
            }
            $image = (string) $image;

            // ---- distribution (constante para PF) ----
            $distribution = 'FIVERS';

            // ---- filtros básicos ----
            if ($providerOpt) {
                // se quiser filtrar via --provider="Pragmatic Play"
                if (!Str::of($providerName)->lower()->contains(Str::of($providerOpt)->lower())) {
                    continue;
                }
            }
            if ($providerName === '' || $gameCode === '') {
                // registro inválido
                continue;
            }

            // Normaliza provider/game para o slug (evita barras que quebram a rota)
            $safeProvider = str_replace(['/', '\\'], '-', $providerName);
            $safeGame     = str_replace(['/', '\\'], '-', $gameCode);

            $slug = 'casino/' . $safeProvider . '/' . $safeGame;

            // Upsert
            $record = GamesApi::updateOrCreate(
                ['slug' => $slug],
                [
                    'provider_name' => $providerName,
                    'name'          => $name,
                    'image'         => $image,
                    'order_value'   => 0,
                    'distribution'  => $distribution,
                    'game_id'       => $gameCode,
                    // NEM SEMPRE 'status' está no fillable; setaremos logo abaixo.
                ]
            );

            // Garante status=1 mesmo se não estiver no $fillable
            $record->status = 1;
            $record->save();

            $count++;
        }

        $this->info("Sincronizados/atualizados: {$count} jogos.");
        $this->line("Dica: abra /casino/category/all no front ou GET /api/casino/games para ver a listagem.");
        return self::SUCCESS;
    }
}

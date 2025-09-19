<?php

namespace App\Http\Controllers;

use App\Models\BetRiskLog;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Config;
use App\Models\GameHistory;
use App\Models\GamesApi;
use App\Traits\Providers\{ElloProvider, HypetechProvider, TLTProvider, FiversProvider, XGamingProvider};
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CasinoController extends Controller
{
    use HypetechProvider, TLTProvider, FiversProvider, XGamingProvider, ElloProvider;

    protected Config $configs;

    public function __construct()
    {
        $this->configs = Config::first();
    }

    public function homeGames (Request $request)
    {
        $categories = Category::query()
            ->where('is_active', 1)
            ->orderBy('position', 'ASC')
            ->get()
            ->each(function ($category) {
                $category->games = $category->games();
            });

        $top_games = cache()->remember('top_games', today()->addWeek(), function () {
            $topBets = GameHistory::query()
                ->selectRaw('game, count(*) as bets_count')
                ->where('action', 'bet')
                ->groupBy('game')
                ->orderByDesc('bets_count')
                // ->whereDate('created_at', '>=', today()->subDays(30))
                ->limit(10)
                ->get();

            if ($topBets->count() == 0) return collect([]);

            $gameIds = $topBets->pluck('game');
            $games = GamesApi::whereIn('game_id', $gameIds)
                ->orderByRaw('FIELD(game_id, ' . $gameIds->map(fn ($item) => "'$item'")->join(',') . ')')
                ->get();

            return $games;
        });

        $top_winners = cache()->remember('top_winners', today()->addDay(), function () use ($top_games) {
            $randomGames = GamesApi::inRandomOrder()
                ->take(10 - $top_games->count())
                ->get();
            $randomGames = $top_games->merge($randomGames);

            return collect(range(1, 10))
                ->map(function () use ($randomGames) {
                    $game = $randomGames->random();
                    return [
                        'name' => fake('pt_BR')->firstName() . ' ' . str(fake('pt_BR')->lastName())->take(4)->mask('*', 1),
                        'amount' => rand(420, 950),
                        'game_name' => $game->name,
                        'game_image' => $game->image,
                    ];
                })
                ->sortByDesc('amount')
                ->values();
        });

        return compact('top_games', 'top_winners', 'categories');
    }

    public function game (Request $request)
    {
        $game = GamesApi::where('slug', 'casino/' . $request->slug)->firstOrFail();

        abort_if($game->status != 1, 404);

        return $game;
    }

    public function filter (Request $request)
    {
        $request->validate([
            'term'     => ['sometimes', 'string', 'max:100'],
            'provider' => ['sometimes', 'string'],
            'page'     => ['sometimes', 'integer'],
            'per_page'  => ['sometimes', 'integer', 'max:50'],
        ]);

        $games = GamesApi::query()
            ->where('status', 1)
            ->when($request->has('search'), function ($query) use ($request) {
                $search = $this->prepareSearchQuery($request->search);

                $query->whereRaw('LOWER(name) LIKE ?', "%$search%");
            })
            ->when($request->has('provider'), function ($query) use ($request) {
                $provider = str_replace('jogos-', '', $request->provider);
                $provider = str_replace('-', ' ', $provider);

                $query->where('provider_name', $provider);
            })
            ->orderBy('order_value', 'ASC')
            ->paginate(
                page: $request->page,
                perPage: $request->per_page ?? 50
            );

        return $games;
    }

    public function category (string $slug)
    {
        $category = Category::where('category_provider', $slug)->firstOrFail();
        $category->games = $category->games();

        return $category;
    }

    public function startGame (\Illuminate\Http\Request $request)
{
    $request->validate([
        'slug'     => ['required', 'string'],   // formato: "Provider/GameCode"
        'platform' => ['required', 'in:WEB,MOBILE'],
    ]);

    $user = auth()->user(); // precisa estar logado (RequiresLogin do seu front)
    if (!$user) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    // (a) obtemos o jogo pela slug só para pegar nome/imagem/etc se já existir
    $game = \App\Models\GamesApi::where('slug', 'casino/' . $request->slug)->first();

    // (b) game_code = última parte da slug
    $gameCode = (string) str($request->slug)->after('/'); // "Provider/GameCode" -> "GameCode"

    // (c) user_code e saldo (ajuste aqui ao seu schema)
    $userCode    = $user->email ?? ('user-'.$user->id);
    $userBalance = (float) ($user->wallet_balance ?? 0.0);

    try {
        // (d) chama PlayFivers direto via service
        $pf = app(\App\Services\PlayfiversService::class);

        $resp = $pf->launchGame(
            userCode:     $userCode,
            gameCode:     $gameCode,
            gameOriginal: false,              // normalmente false p/ agregador
            userBalance:  $userBalance,
            lang:         null                // usa padrão do config (pt)
        );

        // Esperado: ['status'=>bool,'msg'=>...,'launch_url'=>..., ...]
        if (!($resp['status'] ?? false)) {
            return response()->json([
                'error'   => 'LAUNCH_FAILED',
                'message' => $resp['msg'] ?? 'unknown_error',
                'raw'     => $resp,
            ], 422);
        }

        $url = $resp['launch_url'] ?? null;
        if (!$url || !preg_match('~^https?://~i', $url)) {
            return response()->json([
                'error'   => 'LAUNCH_URL_EMPTY',
                'message' => 'launch_url ausente ou inválida',
                'raw'     => $resp,
            ], 422);
        }

        // Monta payload final: mantemos compat com seu front (gameURL/launch_url/url)
        $payload = [
            'id'            => $game->id ?? null,
            'name'          => $game->name ?? ($resp['name'] ?? null),
            'image'         => $game->image ?? null,
            'provider_name' => $game->provider_name ?? (string) str($request->slug)->before('/'),
            'slug'          => $game->slug ?? ('casino/'.$request->slug),
            'gameURL'       => $url,
            'launch_url'    => $url,
            'url'           => $url,
        ];

        return response()->json($payload, 200);

    } catch (\Throwable $e) {
        \Log::error('PlayFivers launch exception', [
            'error' => $e->getMessage(),
            'slug'  => $request->slug,
        ]);
        return response()->json([
            'error'   => 'EXCEPTION',
            'message' => $e->getMessage(),
        ], 500);
    }
}


    public function gaming ()
    {
        $risks = BetRiskLog::where('email', auth()->user()->email)
            ->where('status', 'pending')
            ->get();

        $risks->each->update(['status' => 'viewed']);

        return $risks->count();
    }

    protected function prepareSearchQuery (string $query)
    {
        $acentos = [
            'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
            'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o', 'ö' => 'o',
            'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A', 'Ä' => 'A',
            'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ó' => 'O', 'Ò' => 'O', 'Õ' => 'O', 'Ô' => 'O', 'Ö' => 'O',
            'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
            'ç' => 'c', 'Ç' => 'C',
        ];

        return (string) str($query)
            ->trim()
            ->squish()
            ->lower()
            // Remove acentuação
            ->pipe(fn ($value) => strtr($value, $acentos))
            // Remove pontuação
            ->replaceMatches('/[^\w\s]/u', '');
    }
}

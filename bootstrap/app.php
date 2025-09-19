<?php

use App\Http\Middleware\BannedMiddleware;
use App\Http\Middleware\LanguageMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
        ]);
        $middleware->append(LanguageMiddleware::class);
        $middleware->appendToGroup('api', [
            BannedMiddleware::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'bsp/*',
            'gateway/*',
            'external/*',
            'webhooks/*',
            'tltprod/*',
            'sportbook/*',
            'hypetech/*',
            'gold_api',
            'webhook_ellojogos',
            'xgamingprovider_api_check',
            'txn-sp-check',
            'citrexpay/*'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

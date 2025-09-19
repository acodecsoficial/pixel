<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ElloController;
use App\Http\Controllers\ExternalApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\HypertechController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\FiversController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\XGamingController;
use App\Http\Controllers\BlueOceanController;
use App\Http\Controllers\TltController;
use App\Http\Middleware\TltMiddleware;
use Laravel\Socialite\Facades\Socialite;

// Auth
Route::get('/auth/google/redirect', function () {
    return Socialite::driver('google')->scopes(['profile', 'email', 'https://www.googleapis.com/auth/user.phonenumbers.read'])->redirect();
});
Route::get('/auth/google/callback', [AuthController::class, 'loginWithGoogle']);
Route::post('/auth/send-password-reset', [AuthController::class, 'sendResetPasswordMail']);
Route::view('/account/password-reset', 'auth.password-reset')->middleware('signed')->name('password.reset');
Route::post('/account/password-reset', [AuthController::class, 'resetPassword'])->middleware('signed');

// Webhook PrimePag

Route::group(['prefix' => 'gateway'], function () {
    Route::post('primePagCallback', [WebhookController::class, 'webhookPrimePag']);
});

// Webhook BSPay

Route::group(['prefix' => 'bsp'], function () {
    Route::post('check', [WebhookController::class, 'paymentBspay']);
    // Route::post('check_out', [WebhookController::class, 'webhookBspayWithdraw']);
});

Route::group(['prefix' => 'citrexpay'], function () {
    Route::post('check', [WebhookController::class, 'paymentCitrexpay']);
});

// All Webhooks

Route::group(['prefix' => 'webhooks'], function () {
    Route::post('check', [WebhookController::class, 'webhookEzzebank']);
    Route::post('checkwithdraw', [WebhookController::class, 'checkWithdrawByTransactionId']);

    Route::group(['prefix' => 'hypetech'], function () {
        Route::post('register', [HypertechController::class, 'registerBet']);
        Route::post('rollback', [HypertechController::class, 'registerRollback']);
        Route::post('rewards', [HypertechController::class, 'registerRewards']);
        Route::post('balance', [HypertechController::class, 'getUserBalance']);
    });
});

Route::group(['prefix' => 'tltprod'], function () {
    Route::group(['prefix' => 'webhook', 'middleware' => TltMiddleware::class], function () {
        Route::post('authenticate', [TltController::class, 'authenticate']);
        Route::post('balance', [TltController::class, 'balance']);
        Route::post('changebalance', [TltController::class, 'changebalance']);
        Route::post('status', [TltController::class, 'status']);
        Route::post('cancel', [TltController::class, 'cancel']);
        Route::post('finishround', [TltController::class, 'finishround']);
    });
});

Route::group(['prefix' => 'sportbook'], function () {
    Route::post('webhook', [SportController::class, 'webhook']);
});

Route::post('/gold_api', [FiversController::class, 'webhookFivers']);
Route::post('/webhook_ellojogos', [ElloController::class, 'webhookEllo']);
Route::post('/txn-sp-check', [WebhookController::class, 'paymentSuitpay']);
Route::post('/xgamingprovider_api_check', [XGamingController::class, 'webhookXGaming']);
Route::get('/webhook_blueoceangaming', [BlueOceanController::class, 'webhookBlueOcean']);


// External API Routes

Route::group(['prefix' => 'external'], function () {
    Route::post('authenticate', [ExternalApiController::class, 'login']);
    Route::post('check-balance', [ExternalApiController::class, 'checkBalance']);
    Route::post('change-balance', [ExternalApiController::class, 'changeBalance']);
});

// Render other routes in the frontend
Route::fallback(SiteController::class);

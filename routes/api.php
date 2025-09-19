<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CasinoController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\WithdrawController;


// Auth routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::prefix('/casino')->group(function () {
    Route::get('/home-games', [CasinoController::class, 'homeGames']);
    Route::get('/categories/{slug}', [CasinoController::class, 'category']);
    Route::get('/games', [CasinoController::class, 'filter']);
});

Route::get('/coupons/{code}', [CouponController::class, 'validate']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'infos']);
    Route::patch('/user', [UserController::class, 'update']);
    Route::post('/user/change-password', [UserController::class, 'changePassword']);
    Route::get('/user/wallet', [UserController::class, 'wallet']);
    Route::get('/user/affiliation-state', [UserController::class, 'getAffiliationState']);
    Route::post('/user/request-affiliate', [UserController::class, 'requestAffiliateAccess']);

    Route::post('/casino/start-game', [CasinoController::class, 'startGame']);
    Route::get('/casino/gaming', [CasinoController::class, 'gaming']);

    Route::post('/wallet/add-credit', [GatewayController::class, 'generateQrCode']);
    Route::post('/wallet/withdraw', [WithdrawController::class, 'withdraw']);
    Route::post('/wallet/request-rewards', [WithdrawController::class, 'withdrawAffiliate']);
    Route::get('/wallet/withdrawals', [WithdrawController::class, 'listWithdrawals']);
    Route::get('/wallet/deposits', [GatewayController::class, 'listDeposits']);
    Route::get('/wallet/deposits/{deposit}/status', [GatewayController::class, 'getDepositStatus']);

    Route::post('/sportbook/launch', [SportController::class, 'launchSportbook']);

    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

Route::post('/send-withdraw-email', [SiteController::class, 'sendWithdrawEmail']);





Route::fallback(function () {
    return response()->json([
        'message' => 'Route Not Found'
    ], 404);
});

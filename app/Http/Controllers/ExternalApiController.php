<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ExternalApiController extends Controller
{
    protected $config;

    public function __construct()
    {
        $this->config = Config::first();
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'tenant_id' => ['required']
        ]);

        if (!Hash::check($request->tenant_id, $this->config->tenant_id)) {
            return response()->json([
                'error' => "Tenant ID not valid"
            ], 400);
        }

        $credentials = $request->only(['email', 'password']);

        $user = User::where('email', $request->email)->first();
        if ($user && !is_null($user->deleted_at)) {
            return response()->json([
                'error' => __("back.account-not-found")
            ], 400);
        }

        if (!Auth::attempt($credentials, true)) {
            return response()->json([
                'message' => __('auth.failed'),
            ], 401);
        }

        return [
            'email' => $user->email,
            'document' => $user->cpf,
            'ddi' => $user->ddi,
            'phoneNumber' => $user->phone,
            'balance' => $user->wallet,
            'bonus_balance' => $user->wallet_bonus,
            'username' => $user->username,
            'inviter' => $user->inviter,
            'banned' => $user->banned,
            'affiliate' => $user->affiliate,
            'affiliate_code' => $user->code,
            'register_date' => $user->created_at,
            'info_affiliate' => [
                'balance' => $user->referRewards,
                'refer_percent' => $user->referFake + $user->referPercent,
                'cpa_comission' => $user->value_cpa,
                'sub_affiliate_percent' => $user->subPercent,
            ]
        ];
    }

    public function checkBalance(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'tenant_id' => ['required'],
        ]);

        if (!Hash::check($request->tenant_id, $this->config->tenant_id)) {
            return response()->json([
                'error' => "Tenant ID not valid"
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if ($user && !is_null($user->deleted_at)) {
            return response()->json([
                'error' => __("back.account-not-found")
            ], 400);
        }

        return [
            'balance' => $user->wallet,
            'bonus_balance' => $user->wallet_bonus,
            'affiliate_balance' => $user->referRewards
        ];
    }

    public function changeBalance(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'amount' => ['required', 'numeric'],
            'wallet_type' => ['required', 'string'],
            'tenant_id' => ['required'],
        ]);

        if (!Hash::check($request->tenant_id, $this->config->tenant_id)) {
            return response()->json([
                'error' => "Tenant ID not valid"
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if ($user && !is_null($user->deleted_at)) {
            return response()->json([
                'error' => __("back.account-not-found")
            ], 400);
        }

        if ($request->wallet_type === "credit") {
            $user->increment('wallet', $request->amount);
        }

        if ($request->wallet_type === "bonus") {
            $user->increment('wallet_bonus', $request->amount);
        }

        if ($request->wallet_type === "affiliate") {
            $user->increment('referRewards', $request->amount);
        }

        return [
            'msg' => 'Balance updated',
            'balance' => $user->wallet,
            'bonus_balance' => $user->wallet_bonus,
            'affiliate_balance' => $user->referRewards
        ];
    }
}

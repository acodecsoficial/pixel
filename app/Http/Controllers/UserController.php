<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\AffiliateApproval;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function infos (Request $request)
    {
        $user = $request->user();

        $user->referPercent = $user->referPercent + $user->referFake;

        return $user;
    }

    public function wallet ()
    {
        $user = auth()->user();

        return [
            "balance" => $user->wallet,
            "credit" => $user->wallet,
            "available_value" => $user->wallet,
            "bonus" => $user->wallet_bonus,
            "max_withdraw_amount" => 0,
            "withdraw_enabled" => 1,
            "withdraw_enable_now" => true,
            "withdraw_next_date" => null,
            "bonus_wallet_result" => [
                "credit" => $user->wallet_bonus * 100,
                "credit_hold" => 0,
                "sports_amount_hold" => 0,
                "sports_rollover_count" => 0,
                "casino_amount_hold" => 0,
                "casino_rollover_count" => 0,
                "rollover_type" => null,
                "expiry_datetime" => null,
            ]
        ];
    }

    public function update (Request $request)
    {
        $data = $request->validate([
            'name'     => ['sometimes', 'string', 'max:80'],
            'ddi'      => ['sometimes'],
            'phone'    => ['sometimes', 'celular_com_ddd'],
            'cpf'      => ['sometimes', 'cpf', Rule::unique('users')->ignore(auth()->id())],
            'username' => ['sometimes', 'alpha_num', 'min:3', 'max:20', Rule::unique('users')->ignore(auth()->id())],
            'avatar'   => ['sometimes', 'string', 'url'],
        ], [
            'cpf.unique' => __('back.user.cpf-unique'),
        ]);

        $user = auth()->user();

        if (isset($data['cpf'])) {
            $data['cpf'] = str_replace(['.', '-'], '', $data['cpf']);

            if (isset($user->cpf) && $data['cpf'] !== $user->cpf) {
                return response()->json([
                    'message' => __('back.user.cpf-cannot-changed'),
                    'success' => false,
                ], 400);
            }
        }

        $user->fill($data);
        $user->save();

        return [
            'message' => __('back.user.update'),
            'success' => true,
        ];
    }

    public function changePassword (Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password'     => ['required', 'confirmed', Password::min(6)],
        ]);

        $user = $request->user();
        $user->password = bcrypt($request->new_password);
        $user->save();

        return [
            'message' => __('back.user.changepass'),
            'success' => true,
        ];
    }
}

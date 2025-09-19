<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function validate (string $code)
    {
        $coupon = DB::table('codes')
            ->where('code', strtoupper($code))
            ->first();

        abort_if(!$coupon, 404, __('back.coupon-not-found'));
        abort_if(!$coupon->activate, 403, __('back.coupon-expired'));

        return response()->json([
            "minimum_deposit_value" => $coupon->amountactive,
            "coupon" => $coupon->code,
            "value" => $coupon->amount,
            "type" => $coupon->tipobonus == 1 ? 'real' : 'bonus',
        ]);
    }
}

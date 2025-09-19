<?php

namespace App\Traits\Gateways;

use App\Models\PaymentHistory;
use Illuminate\Support\Facades\DB;

trait CheckCoupon
{
    public function checkCoupon($couponCode, $amount, $email)
    {
        $coupon = DB::table('codes')
            ->where('code', strtoupper($couponCode))
            ->first();

        if(!$coupon){
            return response()->json([
                'success' => false,
                'message' => __('back.gateway.coupon-not-found')
            ], 400);
        }

        $usedCoupon = PaymentHistory::where('coupon', $coupon->code)
        ->where('user', $email)
        ->exists();

        if($usedCoupon){
            return response()->json([
                'success' => false,
                'message' => __('back.gateway.coupon-already-used')
            ], 400);
        }

        if(!$coupon->activate){
            return response()->json([
                'success' => false,
                'message' => __('back.gateway.coupon-expired')
            ], 400);
        }

        if($amount < $coupon->amountactive) {
            return response()->json([
                'success' => false,
                'message' => __('back.gateway.coupon-minimum') . $coupon->amountactive
            ], 400);
        }

        return $coupon->code;

    }

    public function payCoupon($transaction){
        $coupon = DB::table('codes')
            ->where('code', strtoupper($transaction->coupon))
            ->first();

        if(!$coupon){
            $coupon = null;
            return $coupon;
        }

        if($coupon->tipobonus == 1){
            $coupon->type = 'real';
        } else {
            $coupon->type = 'bonus';
        }

        return $coupon;
    }
}

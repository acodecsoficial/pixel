<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Config;
use App\Traits\Gateways\CheckCoupon;
use Illuminate\Http\Request;
use App\Models\PaymentHistory;
use App\Traits\AffiliateRewards;
use App\Traits\RolloverSum;
use App\Traits\SendMail;
use App\Traits\SendWebhook;

class WebhookController extends Controller
{
    use AffiliateRewards, CheckCoupon, RolloverSum, SendMail, SendWebhook;

    public $rollover;
    public $bonus_type;
    public $bonus_percent;
    public $base_rollover;

    public function __construct()
    {
        $configs = Config::first();

        $this->rollover      = $configs->rollover ?? 0;
        $this->bonus_type    = $configs->bonus_type ?? 'bonus';
        $this->base_rollover = $configs->base_rollover ?? 0;
        $this->bonus_percent = $configs->bonus_percentage ?? 0;
    }

    public function paymentBspay(Request $request)
    {
        try {
            $data = $request->requestBody;
            $amount = $data['amount'];

            $transaction = PaymentHistory::where('offer_id', $data["transactionId"])->first();

            if (!isset($transaction)) {
                throw new \Exception('ID not found');
            }

            if ($transaction->offer_state == 'paid') {
                throw new \Exception('Payment already confirmed');
            }

            $user = User::where('email', $transaction->user)->first();

            if (!isset($user)) {
                throw new \Exception('User not found');
            }

            if ($transaction && $user) {

                $bonus = 0;
                $rollover = 0;
                $xpnewbonus = '150';
                $credit = $amount;
                $creditreal = $amount;
                $bonus_rollover = $transaction->rollover;

                if ($bonus_rollover) {
                    $dataRoll = (object) [
                        'amount' => $amount,
                        'rollover' => $this->rollover,
                        'bonus_percent' => $this->bonus_percent,
                        'user' => $user
                    ];

                    $rollData = $this->sumRollover($dataRoll);
                    if ($rollData) {
                        $bonus += $rollData->bonus;
                        $rollover = $rollData->rollover;
                    }
                } else {
                    $rollover = $amount * $this->base_rollover;
                }

                if ($transaction->coupon) {
                    $coupon = $this->payCoupon($transaction);
                    $credit += $coupon->amount;

                    if ($coupon->type == 'real') {
                        $creditreal += $coupon->amount;
                    } else {
                        $bonus += $coupon->amount;
                    }
                }

                $transaction->update([
                    'offer_state' => 'paid',
                    'credited'    => $credit
                ]);

                if($this->bonus_type ==  'real') {
                    $creditreal += $bonus;
                    $bonus = 0;
                }

                $this->cpaAffiliate($user, $amount);

                $user->increment('wallet', $creditreal);
                $user->increment('wallet_bonus', $bonus);
                $user->increment('deposit_sum', $amount);
                $user->increment('deposit_sum_code', 1);
                $user->increment('xp', $xpnewbonus);
                $user->increment('rollover', $rollover);
                $user->update([
                    'last_deposit' => $amount,
                    'anti_bot' => 0
                ]);

                try {
                    $this->sendDepositMail($user, $amount);
                    $this->paymentPaidHook($transaction);
                } catch (\Exception $e) {
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Transaction updated successfully'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function webhookEzzebank(Request $request)
    {
        try {

            $data = $request->requestBody;
            $amount = $data['amount'];

            $transaction = PaymentHistory::where('offer_id', $data["transactionId"])->first();

            if (!isset($transaction)) {
                throw new \Exception('ID not found');
            }

            if ($transaction->offer_state == 'paid') {
                throw new \Exception('Payment already confirmed');
            }

            $user = User::where('email', $transaction->user)->first();

            if (!isset($user)) {
                throw new \Exception('User not found');
            }

            if ($transaction && $user) {

                $bonus = 0;
                $rollover = 0;
                $xpnewbonus = '150';
                $credit = $amount;
                $creditreal = $amount;
                $bonus_rollover = $transaction->rollover;

                if ($bonus_rollover) {
                    $dataRoll = (object) [
                        'amount' => $amount,
                        'rollover' => $this->rollover,
                        'bonus_percent' => $this->bonus_percent,
                        'user' => $user
                    ];

                    $rollData = $this->sumRollover($dataRoll);
                    if ($rollData) {
                        $bonus = $rollData->bonus;
                        $rollover = $rollData->rollover;
                    }
                } else {
                    $rollover = $amount * $this->base_rollover;
                }

                if ($transaction->coupon) {
                    $coupon = $this->payCoupon($transaction);
                    $credit += $coupon->amount;

                    if ($coupon->type == 'real') {
                        $creditreal += $coupon->amount;
                    } else {
                        $bonus += $coupon->amount;
                    }
                }

                $transaction->update([
                    'offer_state' => 'paid',
                    'credited'    => $credit
                ]);

                $this->cpaAffiliate($user, $amount);

                $user->increment('wallet', $creditreal);
                $user->increment('wallet_bonus', $bonus);
                $user->increment('deposit_sum', $amount);
                $user->increment('deposit_sum_code', 1);
                $user->increment('xp', $xpnewbonus);
                $user->increment('rollover', $rollover);
                $user->update([
                    'last_deposit' => $amount,
                    'anti_bot' => 0
                ]);

                try {
                    $this->sendDepositMail($user, $amount);
                    $this->paymentPaidHook($transaction);
                } catch (\Exception $e) {
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Transaction updated successfully'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function webhookPrimePag(Request $request)
    {
        try {

            if ($request->message['status'] != 'paid') {
                throw new \Exception('Transaction updated successfully');
            }

            $transaction_id = $request->message['reference_code'];
            $transaction = PaymentHistory::where('offer_id', $transaction_id)->first();

            if (!isset($transaction)) {
                throw new \Exception('ID not found');
            }

            if ($transaction->offer_state == 'paid') {
                throw new \Exception('Payment already confirmed');
            }

            $user = User::where('email', $transaction->user)->first();

            if (!isset($user)) {
                throw new \Exception('User not found');
            }

            $value = $request->message['value_cents'] / 100;

            if ($transaction && $user) {

                $bonus = 0;
                $rollover = 0;
                $xpnewbonus = '150';
                $credit = $value;
                $creditreal = $value;
                $bonus_rollover = $transaction->rollover;

                if ($bonus_rollover) {
                    $dataRoll = (object) [
                        'amount' => $value,
                        'rollover' => $this->rollover,
                        'bonus_percent' => $this->bonus_percent,
                        'user' => $user
                    ];

                    $rollData = $this->sumRollover($dataRoll);
                    if ($rollData) {
                        $bonus = $rollData->bonus;
                        $rollover = $rollData->rollover;
                    }
                } else {
                    $rollover = $value * $this->base_rollover;
                }

                if ($transaction->coupon) {
                    $coupon = $this->payCoupon($transaction);
                    $credit += $coupon->amount;

                    if ($coupon->type == 'real') {
                        $creditreal += $coupon->amount;
                    } else {
                        $bonus += $coupon->amount;
                    }
                }

                $transaction->update([
                    'offer_state' => 'paid',
                    'credited'    => $credit
                ]);

                $this->cpaAffiliate($user, $value);

                $user->increment('wallet', $creditreal);
                $user->increment('wallet_bonus', $bonus);
                $user->increment('deposit_sum', $value);
                $user->increment('deposit_sum_code', 1);
                $user->increment('xp', $xpnewbonus);
                $user->increment('rollover', $rollover);
                $user->update([
                    'last_deposit' => $value,
                    'anti_bot' => 0
                ]);

                try {
                    $this->sendDepositMail($user, $value);
                    $this->paymentPaidHook($transaction);
                } catch (\Exception $e) {
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Transaction updated successfully'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function paymentSuitpay(Request $request)
    {
        try {

            $amount = $request->value;

            $transaction = PaymentHistory::where('offer_id', $request->idTransaction)->first();

            if (!isset($transaction)) {
                throw new \Exception('ID not found');
            }

            if ($transaction->offer_state == 'paid') {
                throw new \Exception('Payment already confirmed');
            }

            $user = User::where('email', $transaction->user)->first();

            if (!isset($user)) {
                throw new \Exception('User not found');
            }

            if ($transaction && $user) {

                $bonus = 0;
                $rollover = 0;
                $xpnewbonus = '150';
                $credit = $amount;
                $creditreal = $amount;
                $bonus_rollover = $transaction->rollover;

                if ($bonus_rollover) {
                    $dataRoll = (object) [
                        'amount' => $amount,
                        'rollover' => $this->rollover,
                        'bonus_percent' => $this->bonus_percent,
                        'user' => $user
                    ];

                    $rollData = $this->sumRollover($dataRoll);
                    if ($rollData) {
                        $bonus = $rollData->bonus;
                        $rollover = $rollData->rollover;
                    }
                } else {
                    $rollover = $amount * $this->base_rollover;
                }

                if ($transaction->coupon) {
                    $coupon = $this->payCoupon($transaction);
                    $credit += $coupon->amount;

                    if ($coupon->type == 'real') {
                        $creditreal += $coupon->amount;
                    } else {
                        $bonus += $coupon->amount;
                    }
                }

                $transaction->update([
                    'offer_state' => 'paid',
                    'credited'    => $credit
                ]);

                $this->cpaAffiliate($user, $amount);

                $user->increment('wallet', $creditreal);
                $user->increment('wallet_bonus', $bonus);
                $user->increment('deposit_sum', $amount);
                $user->increment('deposit_sum_code', 1);
                $user->increment('xp', $xpnewbonus);
                $user->increment('rollover', $rollover);
                $user->update([
                    'last_deposit' => $amount,
                    'anti_bot' => 0
                ]);

                try {
                    $this->sendDepositMail($user, $amount);
                    $this->paymentPaidHook($transaction);
                } catch (\Exception $e) {
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Transaction updated successfully'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function paymentCitrexpay(Request $request)
    {
        try {
            $data = $request->all();
            $amount = $data['amount'];

            $transaction = PaymentHistory::where('offer_id', $data["id"])->first(); 

            if (!isset($transaction)) {
                throw new \Exception('ID not found');
            }

            if ($transaction->offer_state == 'paid') {
                throw new \Exception('Payment already confirmed');
            }

            $user = User::where('email', $transaction->user)->first();

            if (!isset($user)) {
                throw new \Exception('User not found');
            }

            if ($transaction && $user) {

                $bonus = 0;
                $rollover = 0;
                $xpnewbonus = '150';
                $credit = $amount;
                $creditreal = $amount;
                $bonus_rollover = $transaction->rollover;

                if ($bonus_rollover) {
                    $dataRoll = (object) [
                        'amount' => $amount,
                        'rollover' => $this->rollover,
                        'bonus_percent' => $this->bonus_percent,
                        'user' => $user
                    ];

                    $rollData = $this->sumRollover($dataRoll);
                    if ($rollData) {
                        $bonus += $rollData->bonus;
                        $rollover = $rollData->rollover;
                    }
                } else {
                    $rollover = $amount * $this->base_rollover;
                }

                if ($transaction->coupon) {
                    $coupon = $this->payCoupon($transaction);
                    $credit += $coupon->amount;

                    if ($coupon->type == 'real') {
                        $creditreal += $coupon->amount;
                    } else {
                        $bonus += $coupon->amount;
                    }
                }

                $transaction->update([
                    'offer_state' => 'paid',
                    'credited'    => $credit
                ]);

                if($this->bonus_type ==  'real') {
                    $creditreal += $bonus;
                    $bonus = 0;
                }

                $this->cpaAffiliate($user, $amount);

                $user->increment('wallet', $creditreal);
                $user->increment('wallet_bonus', $bonus);
                $user->increment('deposit_sum', $amount);
                $user->increment('deposit_sum_code', 1);
                $user->increment('xp', $xpnewbonus);
                $user->increment('rollover', $rollover);
                $user->update([
                    'last_deposit' => $amount,
                    'anti_bot' => 0
                ]);

                try {
                    $this->sendDepositMail($user, $amount);
                    $this->paymentPaidHook($transaction);
                } catch (\Exception $e) {
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Transaction updated successfully'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 200);
        }
    }
}

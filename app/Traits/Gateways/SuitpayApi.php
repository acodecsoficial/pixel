<?php

namespace App\Traits\Gateways;

use Carbon\Carbon;
use App\Models\Gateway;
use Illuminate\Support\Str;
use App\Models\PaymentHistory;
use App\Traits\Gateways\CheckCoupon;
use App\Traits\SendWebhook;
use Illuminate\Support\Facades\Http;

trait SuitpayApi
{
    use CheckCoupon, SendWebhook;

    public function getSuitpay($request)
    {
        try {

            $user = auth()->user();
            $amount = $request->credit_amount / 100;
            $document = preg_replace("/[\s\.-]/", "", $request->document);
            $accept_bonus = $request->accept_bonus;
            $bonus = $request->bonus;
            $coupon = null;

            if ($request->coupon_code) {
                $coupon = $this->checkCoupon($request->coupon_code, $amount, $user->email);
            }

            $suit = Gateway::where('gateway_name', 'Suitpay')->first();

            $headers = [
                'ci' => $suit->gateway_id,
                'cs' => $suit->gateway_secret
            ];

            $postbackUrl = 'https://' . $_SERVER['SERVER_NAME'] . '/txn-sp-check';
            $dueDate = Carbon::now()->subDays(1);

            $payLoad = [
                'amount'        => $amount,
                'requestNumber' => (string) Str::uuid(),
                'dueDate'       => $dueDate,
                'callbackUrl'  => $postbackUrl,
                'client' => [
                    'name'     => $user->email,
                    'document' => $document,
                    'email'    => $user->email
                ],
            ];

            $response = Http::withHeaders($headers)
                ->post(
                    'https://ws.suitpay.app/api/v1/gateway/request-qrcode',
                    $payLoad
                );

            $responseData = $response->json();

            if ($response->successful()) {
                $paymentCode    = $responseData['paymentCode'];
                $transaction_id = $responseData['idTransaction'];

                $registerPayment = PaymentHistory::create([
                    'user'        => $user->email,
                    'bonus'       => $bonus,
                    'offer_id'    => $transaction_id,
                    'offer_state' => 'pending',
                    'worth'       => $amount,
                    'type'        => 'pix',
                    'provider'    => 'suitpay',
                    'rollover'    => $accept_bonus,
                    'coupon'      => $coupon
                ]);

                $data = [
                    'payment_link' => $paymentCode,
                    'qr_code' => "https://quickchart.io/qr?size=400&text=" . $paymentCode,
                    'value' => $amount,
                    'br_code' => $paymentCode,
                    'deposit_id' => $registerPayment->id,
                ];

                try {
                    $registerPayment->pix_code = $paymentCode;
                    $this->paymentHook($registerPayment);
                } catch (\Exception $e) {
                }

                return response()->json($data, 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Manutenção em andamento, tente novamente daqui 5 minutos.'
                ], 400);
            }
        } catch (\Exception $e) {
            throw $e;
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}

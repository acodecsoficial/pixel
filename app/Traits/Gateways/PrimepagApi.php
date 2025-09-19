<?php

namespace App\Traits\Gateways;

use App\Models\Gateway;
use App\Models\Withdraw;
use Illuminate\Support\Str;
use App\Models\PaymentHistory;
use App\Traits\Gateways\CheckCoupon;
use App\Traits\SendMail;
use App\Traits\SendWebhook;
use Illuminate\Support\Facades\Http;

trait PrimepagApi
{
    use CheckCoupon, SendWebhook, SendMail;

    public function getPrimepag($request)
    {
        $user = auth()->user();
        $dueDate = 86400;
        $amount = $request->credit_amount / 100;
        $amountCents = $request->credit_amount;
        $document = preg_replace("/[\.-]/", "", $request->document);
        $accept_bonus = $request->accept_bonus;
        $bonus = $request->bonus;
        $coupon = null;

        try {

            if ($request->coupon_code) {
                $coupon = $this->checkCoupon($request->coupon_code, $amount);
            }

            $accessToken = $this->getPrimepagAuth();

            $response = Http::withHeaders([
                'Content-Type: application/json',
                'Authorization' => 'Bearer ' . $accessToken
            ])->post('https://api.primepag.com.br/v1/pix/qrcodes', [
                'value_cents' => $amountCents,
                'expiration_time' => $dueDate,
            ]);

            $responseData = $response->json();

            if ($response->successful()) {
                $paymentCode = $responseData['qrcode']['content'];
                $transaction_id = $responseData['qrcode']['reference_code'];

                $registerPayment = PaymentHistory::create([
                    'user'        => $user->email,
                    'bonus'       => $bonus,
                    'offer_id'    => $transaction_id,
                    'offer_state' => 'pending',
                    'worth'       => $amount,
                    'type'        => 'pix',
                    'provider'    => 'primepag',
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
                    'message' => __('back.gateway.no-success-response-api')
                ], 400);
            }
        } catch (\Exception $e) {
            throw $e;
            return response()->json([
                'status' => 'error',
                'message' => __('back.gateway.exception-api')
            ], 400);
        }
    }

    public function withdrawPrimepag($withdrawInfo)
    {
        $user = $withdrawInfo->user;
        $tax = $withdrawInfo->tax;
        $document    = $withdrawInfo->document;
        $amountTotal = $withdrawInfo->amount;
        $amountPay   = $amountTotal;

        try {

            $acessToken = $this->getPrimepagAuth();

            if ($withdrawInfo->amount_tax) {
                $amountPay = $withdrawInfo->amount_tax;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $acessToken,
                'Content-Type' => 'application/json',
            ])
                ->post('https://api.primepag.com.br/v1/pix/payments', [
                    'idempotent_id' => (string) Str::uuid(),
                    'initiation_type' => 'dict',
                    'value_cents' => $amountPay * 100,
                    'pix_key_type' => 'cpf',
                    'pix_key' => $document,
                    'authorized' => true
                ]);

            if ($response->successful()) {

                $user->decrement('wallet', $amountTotal);

                $registerWithdraw = Withdraw::create([
                    'email'  => $user->email,
                    'amount' => $amountTotal,
                    'pix'    => $document,
                    'status' => 1,
                    'auto_withdraw' => 1
                ]);

                $this->sendWithdrawMail($user, $amountTotal);
                $this->withdrawPaidHook($registerWithdraw);

                return response()->json([
                    'success' => true,
                    'message' => __('back.gateway.withdraw-success')
                ], 200);
            } else {
                throw new \Exception("Error when contacting Payment API");
            }
        } catch (\Exception $e) {
            $user->decrement('wallet', $amountTotal);

            $transaction = Withdraw::create([
                'email'  => $user->email,
                'amount' => $amountTotal,
                'pix'    => $document,
                'status' => 16
            ]);

            return response()->json([
                'success' => true,
                'message' => __('back.gateway.withdraw-success')
            ], 200);
        }
    }

    public function getPrimepagAuth()
    {

        $primepagCred = Gateway::where('gateway_name', 'Primepag')->first();

        $primeString = $primepagCred->gateway_id . ':' . $primepagCred->gateway_secret;
        $primeBase64 = base64_encode($primeString);

        $response = Http::withHeaders([
            'Authorization' => 'BASIC ' . $primeBase64,
        ])->asForm()->post('https://api.primepag.com.br/auth/generate_token', [
            'grant_type' => 'client_credentials',
        ]);

        if ($response->successful()) {

            $data = $response->json();
            return $data['access_token'];
        } else {
            throw new \Exception("Error when get API Auth.");
        }
    }
}

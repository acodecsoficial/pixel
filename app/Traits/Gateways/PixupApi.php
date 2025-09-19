<?php

namespace App\Traits\Gateways;

use App\Models\User;
use App\Models\Gateway;
use App\Models\Withdraw;
use Illuminate\Support\Str;
use App\Models\PaymentHistory;
use App\Traits\Gateways\CheckCoupon;
use App\Traits\SendMail;
use App\Traits\SendWebhook;
use Illuminate\Support\Facades\Http;

trait PixupApi
{
    use CheckCoupon, SendMail, SendWebhook;

    public function getPixup($request)
    {
        try {

            $user = auth()->user();
            $amount = $request->credit_amount / 100;
            $document = preg_replace("/[\s\.-]/", "", $request->document);
            $accept_bonus = $request->accept_bonus;
            $bonus = $request->bonus;
            $coupon = null;
            $payerQuestion = 'Email: ' . $user->email . ' | ' . 'Valor: $ ' . $amount;

            if ($request->coupon_code) {
                $coupon = $this->checkCoupon($request->coupon_code, $amount, $user->email);
            }

            $accessToken = $this->getPixupAuth();
            $postbackUrl = 'https://' . $_SERVER['SERVER_NAME'] . '/bsp/check';

            $response = Http::withHeaders([
                'Content-Type: application/json',
                'Authorization' => 'Bearer ' . $accessToken
            ])->post('https://api.pixupbr.com/v2/pix/qrcode', [
                'amount'        => $amount,
                'external_id'   => (string) Str::uuid(),
                'payerQuestion' => $payerQuestion,
                'payer' => [
                    'name'     => $user->email,
                    'document' => $document,
                    'email'    => $user->email
                ],
                'postbackUrl'  => $postbackUrl
            ]);

            $responseData = $response->json();

            if ($response->successful()) {
                $paymentCode    = $responseData['qrcode'];
                $transaction_id = $responseData['transactionId'];

                $registerPayment = PaymentHistory::create([
                    'user'        => $user->email,
                    'bonus'       => $bonus,
                    'offer_id'    => $transaction_id,
                    'offer_state' => 'pending',
                    'worth'       => $amount,
                    'type'        => 'pix',
                    'provider'    => 'bspay',
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

    public function withdrawPixup($withdrawInfo)
    {
        $user = $withdrawInfo->user;
        $document    = $withdrawInfo->document;
        $amountTotal = $withdrawInfo->amount;
        $amountPay   = $amountTotal;
        $transaction_id = $withdrawInfo->transactionId;

        try {
            $acessToken = $this->getPixupAuth();

            if ($withdrawInfo->amount_tax) {
                $amountPay = $withdrawInfo->amount_tax;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $acessToken,
                'Content-Type' => 'application/json',
            ])
                ->post('https://api.pixupbr.com/v2/pix/payment', [
                    'amount' => $amountPay,
                    'description' => 'Saque realizado para: ' . $user->email,
                    'external_id' => (string) $transaction_id,
                    'creditParty' => [
                        'name' => $user->email,
                        'keyType' => 'CPF',
                        'key' => $document,
                        'taxId' => $document,
                    ],
                ]);

            if ($response->successful()) {

                $user->decrement('wallet', $amountTotal);

                $registerWithdraw = Withdraw::create([
                    'email'  => $user->email,
                    'amount' => $amountTotal,
                    'pix'    => $document,
                    'status' => 1,
                    'transactionId' => $transaction_id,
                    'amount_paid'   => $amountPay,
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
            $user->decrement('wallet', $withdrawInfo->amount);

            Withdraw::create([
                'email'  => $user->email,
                'amount' => $amountTotal,
                'pix'    => $document,
                'status' => 16,
                'transactionId' => $transaction_id
            ]);

            return response()->json([
                'success' => true,
                'message' => __('back.gateway.withdraw-success')
            ], 200);
        }
    }

    public function getPixupAuth(): string
    {
        $bspayCred = Gateway::where('gateway_name', 'Pixup')->first();

        $bspayString = $bspayCred->gateway_id . ':' . $bspayCred->gateway_secret;
        $bspayBase64 = base64_encode($bspayString);

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $bspayBase64,
        ])->asForm()->post('https://api.pixupbr.com/v2/oauth/token', [
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

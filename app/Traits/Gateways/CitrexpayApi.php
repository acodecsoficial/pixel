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

trait CitrexpayApi
{
    use CheckCoupon, SendMail, SendWebhook;

    private $apiBaseURL = 'https://citrexpay.net/api/v2';

    public function getCitrexpay($request)
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

            $accessToken = $this->getCitrexpayAuth();
            $postbackUrl = config('app.url') . '/citrexpay/check';

            $payload = [
                'transaction' => [
                    'document' => $document,
                    'ip_address' => '',
                    'email' => $user->email,
                    'name' => $user->email,
                    'amount' => $amount,
                    'webhook' => $postbackUrl,
                    'idempotency_key' => (string) Str::uuid(),
                ]
            ];

            $response = Http::withHeaders([
                'Content-Type: application/json',
                'Authorization' => 'Bearer ' . $accessToken
            ])->post($this->apiBaseURL.'/create_gateway_cash_in', $payload);

            $responseData = $response->json();

            if ($response->successful()) {
                $paymentCode    = 'data:image/png;base64,'.$responseData['qr_code_base64'];
                $typeableLine   = $responseData['typeable_line'];
                $transaction_id = $responseData['id'];

                $registerPayment = PaymentHistory::create([
                    'user'        => $user->email,
                    'bonus'       => $bonus,
                    'offer_id'    => $transaction_id,
                    'offer_state' => 'pending',
                    'worth'       => $amount,
                    'type'        => 'pix',
                    'provider'    => 'citrexpay',
                    'rollover'    => $accept_bonus,
                    'coupon'      => $coupon
                ]);

                $data = [
                    'status' => 'success',
                    'payment_link' => $typeableLine,
                    'qr_code' => $paymentCode,
                    'value' => $amount,
                    'br_code' => $typeableLine,
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

        } catch (\Throwable $e) {
            throw $e;
            return response()->json([
                'status' => 'error',
                'message' => __('back.gateway.exception-api')
            ], 400);
        }
    }

    public function withdrawCitrexpay($withdrawInfo)
    {
        $user = $withdrawInfo->user;
        $document    = $withdrawInfo->document;
        $amountTotal = $withdrawInfo->amount;
        $amountPay   = $amountTotal;
        $transaction_id = $withdrawInfo->transactionId;

        try {
            if ($withdrawInfo->amount_tax) {
                $amountPay = $withdrawInfo->amount_tax;
            }

            $acessToken = $this->getCitrexpayAuth();
            $postbackUrl = config('app.url') . '/citrexpay/check';

            $payload = [
                'transaction' => [
                    'document' => $document,
                    'ip_address' => '',
                    'email' => $user->email,
                    'name' => $user->name,
                    'amount' => $amountPay,
                    'webhook' => $postbackUrl,
                    'pix_key' => $document,
                    'pix_key_type' => 'cpf',
                    'idempotency_key' => (string) Str::uuid(),
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $acessToken,
                'Content-Type' => 'application/json',
            ])
                ->post($this->apiBaseURL.'/create_gateway_cash_out', $payload);

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

    public function getCitrexpayAuth(): string
    {
        $citrexpayCred = Gateway::where('gateway_name', 'Citrexpay')->first();

        return $citrexpayCred->gateway_secret;
    }
}
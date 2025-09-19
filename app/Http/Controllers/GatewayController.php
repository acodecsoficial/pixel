<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Config;
use App\Models\Gateway;
use Illuminate\Http\Request;
use App\Models\PaymentHistory;
use App\Traits\Gateways\{BspayApi, EzzebankApi, PrimepagApi, CheckCoupon, PixupApi, SuitpayApi, CitrexpayApi};
use Carbon\Carbon;

class GatewayController extends Controller
{
    use BspayApi, PixupApi, EzzebankApi, SuitpayApi, PrimepagApi, CitrexpayApi, CheckCoupon;

    public $minpayment;

    public function __construct()
    {

        $config = Config::first();
        $this->minpayment = $config->minimodeposit;
    }

    public function generateQrCode(Request $request)
    {
        try {

            $user = auth()->user();
            $amount = $request->credit_amount / 100;
            $cpfValid = $request->validate([
                'document' => 'required|cpf'
            ]);

            if ($amount < $this->minpayment) {
                return response()->json([
                    'success' => false,
                    'message' => __('back.gateway.deposit-min-value') . $this->minpayment
                ], 400);
            }

            $gatewayActive = Gateway::where('is_active', true)
                ->orderBy('position', 'asc')
                ->first();

            if ($gatewayActive) {
                $methodName = 'get' . $gatewayActive->gateway_name;

                if (method_exists($this, $methodName)) {
                    return $this->{$methodName}($request);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => __("back.gateway.function-not-found")
                    ], 400);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __("back.gateway.gateway-not-active")
                ], 400);
            }
        } catch (\Exception $e) {
            \Log::debug($e);
            return response()->json([
                'success' => false,
                'message' => __("back.gateway.exception")
            ], 400);
        }
    }

    public function listDeposits(Request $request)
    {
        $request->validate([
            'page'     => ['sometimes', 'integer'],
            'per_page' => ['sometimes', 'integer', 'max:50'],
        ]);

        $payments = PaymentHistory::query()
            ->select('offer_state', 'worth', 'created_at', 'rollover')
            ->where('user', auth()->user()->email)
            ->latest()
            ->paginate(
                page: $request->page,
                perPage: $request->per_page
            );

        foreach ($payments->items() as $payment) {
            $payment->amount = $payment->worth * 100;
            $payment->status = $payment->offer_state;
            $payment->created_at = $payment->date;

            $payment->created_at = Carbon::parse($payment->created_at, "UTC")
                ->setTimezone('America/Sao_Paulo')
                ->toDateTimeString();
        }

        return $payments;
    }

    public function getDepositStatus(string $deposit)
    {
        $payment = PaymentHistory::where('id', $deposit)
            ->where('user', auth()->user()->email)
            ->firstOrFail();

        return $payment->offer_state;
    }
}

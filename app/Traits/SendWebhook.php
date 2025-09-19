<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Webhook;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

trait SendWebhook
{
    public function registerHook($user)
    {
        try {
            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'register_date' => $user->created_at,
                'inviter' => $user->inviter,
                'ip_address' => Request::ip()
            ];

            $link = $this->getUrl('register');
            $header = [
                'Content-Type' => 'application/json'
            ];

            Http::withheaders($header)->post($link, $data);
        } catch (\Exception $e) {
        }
    }

    public function loginHook($user)
    {
        try {
            $data = [
                'email' => $user->email,
                'login_date' => Carbon::now(),
                'ip_address' => Request::ip()
            ];

            $link = $this->getUrl('login');
            $header = [
                'Content-Type' => 'application/json'
            ];

            Http::withheaders($header)->post($link, $data);
        } catch (\Exception $e) {
        }
    }

    public function paymentHook($transaction)
    {
        try {
            $user = User::where('email', $transaction->user)
                ->first();

            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'inviter' => $user->inviter,
                'phone' => $user->phone,
                'pix_code' => $transaction->pix_code,
                'status' => $transaction->offer_state,
                'amount' => $transaction->worth,
                'ip_address' => Request::ip()
            ];

            $link = $this->getUrl('payment');

            $header = [
                'Content-Type' => 'application/json'
            ];

            Http::withheaders($header)->post($link, $data);
        } catch (\Exception $e) {
        }
    }

    public function paymentPaidHook($transaction)
    {
        try {
            $user = User::where('email', $transaction->user)
                ->first();

            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'inviter' => $user->inviter,
                'phone' => $user->phone,
                'status' => $transaction->offer_state,
                'amount' => $transaction->worth,
                'ip_address' => Request::ip()
            ];

            $link = $this->getUrl('payment_paid');
            $header = [
                'Content-Type' => 'application/json'
            ];

            Http::withheaders($header)->post($link, $data);
        } catch (\Exception $e) {
        }
    }

    public function withdrawHook($transaction)
    {
        try {
            $status = $this->getStatus($transaction->status);

            $data = [
                'email' => $transaction->email,
                'status' => $status,
                'amount' => $transaction->amount,
                'ip_address' => Request::ip()
            ];

            $link = $this->getUrl('withdraw');

            $header = [
                'Content-Type' => 'application/json'
            ];

            Http::withheaders($header)->post($link, $data);
        } catch (\Exception $e) {
        }
    }

    public function withdrawPaidHook($transaction)
    {
        try {
            $status = $this->getStatus($transaction->status);

            $data = [
                'email' => $transaction->email,
                'status' => $status,
                'amount' => $transaction->amount,
                'ip_address' => Request::ip()
            ];

            $link = $this->getUrl('withdraw_paid');

            $header = [
                'Content-Type' => 'application/json'
            ];

            Http::withheaders($header)->post($link, $data);
        } catch (\Exception $e) {
        }
    }

    public function getStatus($code)
    {
        if ($code == 16) {
            return 'Pending';
        }

        if ($code == 1) {
            return 'Approved';
        }

        if ($code == 420) {
            return 'Declined';
        }
    }

    public function getUrl($method)
    {
        try {
            $webhook = Webhook::first();

            if ($method == 'register') {
                return $webhook->register;
            }

            if ($method == 'login') {
                return $webhook->login;
            }

            if ($method == 'payment') {
                return $webhook->payment;
            }

            if ($method == 'payment_paid') {
                return $webhook->payment_paid;
            }

            if ($method == 'withdraw') {
                return $webhook->withdraw;
            }

            if ($method == 'withdraw_paid') {
                return $webhook->withdraw_paid;
            }
        } catch (\Exception $e) {
        }
    }
}

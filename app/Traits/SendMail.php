<?php

namespace App\Traits;

use App\Mail\DepositMail;
use App\Mail\MakeQRMail;
use App\Mail\RegisterMail;
use App\Mail\WithdrawMail;
use App\Models\SmtpCredential;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

trait SendMail
{
    public function setSmtpCredentials()
    {
        $smtp = SmtpCredential::first();

        if ($smtp) {
            Config::set('mail.host', $smtp->host);
            Config::set('mail.port', $smtp->port);
            Config::set('mail.username', $smtp->username);
            Config::set('mail.password', $smtp->password);
            Config::set('mail.encryption', $smtp->encryption);
            Config::set('mail.from.address', $smtp->from_address);
            Config::set('mail.from.name', $smtp->from_name);
        }
    }

    public function sendRegisterMail($user)
    {
        try {
            $this->setSmtpCredentials();
            Mail::to($user->email)->queue(new RegisterMail($user));
        } catch (\Exception $e) {}
    }

    public function sendDepositMail($user, int $amount)
    {
        try {
            $this->setSmtpCredentials();

            Mail::to($user->email)->queue(new DepositMail($user, $amount));
        } catch (\Exception $e) {}
    }

    public function sendWithdrawMail($user, int $amount)
    {
        try {
            $this->setSmtpCredentials();

            Mail::to($user->email)->sendNow(new WithdrawMail($user, $amount));
        } catch (\Exception $e) {}
    }

    public function sendMakeQrMail($user)
    {
        try {
            $this->setSmtpCredentials();

            Mail::to($user->email)->send(new MakeQRMail($user->email));
        }
        catch (\Exception $e) {}
    }
}

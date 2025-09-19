<?php

namespace App\Console\Commands;

use App\Mail\ResetPasswordMail;
use Illuminate\Console\Command;
use App\Traits\Gateways\CitrexpayApi; // Certifique-se que o namespace está correto
use App\Models\Gateway;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Traits\SendMail;

class TestEmailSendCommand extends Command
{
    use SendMail;

    protected $signature = 'test:email';
    protected $description = 'Testa o envio de email';

    public function handle()
    {
        $this->info('Iniciando teste de envio de email...');

        // 1. Simular um usuário autenticado
        $user = User::where('email',env('TESTER_EMAIL'))->first(); // Pega o primeiro usuário do banco
        if (!$user) {
            $this->error('Nenhum usuário encontrado no banco de dados. Crie um usuário primeiro.');
            return 1;
        }
        auth()->login($user);
        $this->info("Usuário de teste: {$user->email}");

        try {
            $this->sendRegisterMail($user);
            $this->info('Email (sendRegisterMail) enviado com sucesso!');

            $this->sendDepositMail($user, 100);
            $this->info('Email (sendDepositMail) enviado com sucesso!');

            $this->sendWithdrawMail($user, 100);
            $this->info('Email (sendWithdrawMail) enviado com sucesso!');

            Mail::to($user)->sendNow(new ResetPasswordMail('https://pixzinho.com/...', $user));
            $this->info('Email (ResetPasswordMail) enviado com sucesso!');
        } catch (\Throwable $th) {
            $this->error('Erro ao enviar email: ' . $th->getMessage());
            throw $th;
        }
    }
}
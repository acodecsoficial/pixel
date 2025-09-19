<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\Gateways\CitrexpayApi; // Certifique-se que o namespace está correto
use App\Models\Gateway;
use App\Models\User;
use Illuminate\Http\Request;

class TestGatewayCommand extends Command
{
    use CitrexpayApi; // Usa o seu Trait

    protected $signature = 'test:gateway';
    protected $description = 'Testa a integração com um gateway de pagamento de forma isolada';

    public function handle()
    {
        $this->info('Iniciando teste do gateway Citrexpay...');

        // 1. Simular um usuário autenticado
        $user = User::first(); // Pega o primeiro usuário do banco
        if (!$user) {
            $this->error('Nenhum usuário encontrado no banco de dados. Crie um usuário primeiro.');
            return 1;
        }
        auth()->login($user);
        $this->info("Usuário de teste: {$user->email}");

        // 2. Simular dados da requisição
        $request = new Request();
        $request->merge([
            'credit_amount' => 100,
            'document' => '279.683.230-96',
            'accept_bonus' => false,
            'coupon_code' => ''
        ]); // Valor do depósito de teste
        $this->info("Valor do depósito de teste: R$ {$request->credit_amount}");

        // 3. Obter a configuração do gateway do banco de dados
        $config = Gateway::where('gateway_name', 'Citrexpay')->first();
        if (!$config) {
            $this->error('Configuração para o gateway \'Citrexpay\' não encontrada na tabela \'gateways\'.');
            return 1;
        }

        // 4. Chamar o método do seu Trait
        $this->info('Chamando o método getCitrexpay...');
        try {
            $response = $this->getCitrexpay($request, $config);

            // 5. Exibir o resultado
            $this->info('Resposta do método:');
            print_r($response->getData());

            if (isset($response->getData()->status) && $response->getData()->status === 'success') {
                $this->info('\nTeste parece ter sido bem-sucedido! Verifique o banco de dados para a nova transação pendente.');
            } else {
                $this->warn('\nMétodo executado, mas não retornou um status de sucesso. Verifique a resposta acima.');
            }

        } catch (\Exception $e) {
            $this->error('Ocorreu uma exceção durante a execução do método:');
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}
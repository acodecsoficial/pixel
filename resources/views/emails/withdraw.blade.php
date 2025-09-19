<x-layouts.mail :configs="$configs">
    <x-slot name="title">Confirmação de saque</x-slot>
    <x-slot name="content">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color: #10b981; width: 90px; height: 90px; margin: 26px auto; display: block;"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>

        <h2 style="text-align: center; color: {{ $configs->primary_color }}">Saque aprovado com sucesso</h2>

        <div style="background: #0000001a; padding: 30px 22px; border-radius: 10px; text-align: center; margin: 30px 8px;">
            <p style="color: #555555; font-size: 14px; margin: 0">Valor do saque:</p>
            <h2 style="font-weight: 700; color: {{ $configs->primary_color }}; margin: 8px 0 20px; font-size: 40px;">
                $ {{ number_format($amount, 2, ',', '.') }}
            </h2>
            <p style="color: #555555; font-size: 12px; margin: 0">
                {{ now()->format('d/m/Y H:i') }}
            </p>
        </div>

        <p>O valor solicitado foi transferido para a chave PIX cadastrada em sua conta.</p>

        <a href="{{ rtrim($configs->websiteurl, '/') }}/user/wallet?tab=withdrawals" style="display: block; width: 200px; margin: 20px auto; padding: 10px 0; text-align: center; background-color: {{ $configs->primary_color }}; color: #fff; text-decoration: none; font-weight: bold; border-radius: 5px;">Acessar carteira</a>
    </x-slot>
</x-layouts.mail>

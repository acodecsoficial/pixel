<x-layouts.mail :configs="$configs">
    <x-slot name="title">Boas vindas</x-slot>
    <x-slot name="content">
        <h2 style="text-align: center; color: {{ $configs->primary_color }}">Bem-vindo a {{ $configs->website_name }}!</h2>
        <h3 style="text-align: center; color: #444444; font-size: 18px; font-weight: 400; margin: -10px 0 40px;">Confirmação de cadastro</h3>

        <p>E aí, {{ $user->name }}</p>
        <p>Chegou o grande momento: você está oficialmente dentro da {{ $configs->website_name }}!</p>
        <p>O que você encontra por aqui:</p>
        <ul style="list-style-type: none; padding: 0;">
            <li style="margin-bottom: 10px; padding: 0;">🎰 Os melhores jogos de cassino você encontra aqui, prepare-se para se divertir nesta grande variedade de jogos; Slots, Crash Games, Live Casinos e muito mais.</li>
            <li style="margin-bottom: 10px; padding: 0;">💰 Odds que são um Show à Parte: As melhores cotações do mercado, para você fazer sua bet no seu time favorito!</li>
            <li style="margin-bottom: 10px; padding: 0;">🛡️ Jogo Seguro, Tranquilidade 100%: Pode se divertir sem medos. Nossa plataforma é 100% segura.</li>
            <li style="margin-bottom: 10px; padding: 0;">🎁 Saque mais rápido do Brasil: adoramos celebrar os vencedores, por isso aqui você tem o saque mais rápido do Brasil.</li>
        </ul>
        <p>E se tiver alguma dúvida, nosso time de suporte está de plantão 24/7. É só chamar!</p>
        <p>A diversão começa agora. Bora jogar e que a sorte esteja ao seu favor!</p>
        <p>Um abraço, {{ $configs->website_name }}</p>
        <a href="{{ $configs->websiteurl }}" style="display: block; width: 200px; margin: 20px auto; padding: 10px 0; text-align: center; background-color: {{ $configs->primary_color }}; color: #fff; text-decoration: none; font-weight: bold; border-radius: 5px;">Acessar {{ $configs->website_name }}</a>
    </x-slot>
</x-layouts.mail>

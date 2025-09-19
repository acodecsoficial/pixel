<x-layouts.mail :configs="$configs">
    <x-slot name="title">Redefinição de senha</x-slot>
    <x-slot name="content">
        <p>Olá {{ $user->email ?? 'usuário' }},</p>

        <p>Recebemos uma solicitação para redefinir a senha da sua conta. Para continuar, basta clicar no link abaixo e seguir as instruções para recuperar o acesso.</p>

        <p><a href="{{ $url }}">Redefinir minha senha</a></p>

        <p>Se você não solicitou essa alteração, por favor, ignore este e-mail. Sua conta continuará segura.</p>

        <p>Atenciosamente,<br>
        Equipe Santo.Bet</p>
    </x-slot>
</x-layouts.mail>

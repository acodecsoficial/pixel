<x-layouts.auth
    title="Alterar senha"
>
    <form method="POST" class="flex flex-col gap-4">
        @csrf
        @method('POST')

        <label class="block">
            <span class="font-medium required">Nova senha:</span>
            <input
                type="password"
                label="Nova senha"
                name="new_password"
                required
                autocomplete="off"
                minlength="6"
                class="w-full outline-none bg-white/10 rounded-lg px-4 pt-3 pb-3 mt-1 focus-within:bg-white/15"
                placeholder="********"
            >
        </label>

        <label class="block">
            <span class="font-medium required">Confirmar nova senha:</span>
            <input
                type="password"
                label="Confirmar nova senha"
                name="new_password_confirmation"
                autocomplete="off"
                required
                class="w-full outline-none bg-white/10 rounded-lg px-4 pt-3 pb-3 mt-1 focus-within:bg-white/15"
                placeholder="********"
            >
        </label>

        <button type="submit" class="px-4 rounded-md relative hover:opacity-90 active:scale-95 transition-transform disabled:opacity-30 disabled:pointer-events-none bg-primary bg-gradient-to-b from-black/15 to-white/15 text-black w-full py-3 mt-4 text-xl font-semibold shadow-md shadow-primary/40">Alterar senha</button>
    </form>
</x-layouts.auth>

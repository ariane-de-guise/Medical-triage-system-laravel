<x-action-section>
    <x-slot name="title">Sessions connectées</x-slot>
    <x-slot name="description">Déconnectez les autres navigateurs et appareils qui ont accès à votre compte.</x-slot>

    <x-slot name="content">
        <div class="rounded-3xl bg-slate-50 p-5">
            <h3 class="text-xl font-black text-slate-900">Sécuriser les sessions</h3>
            <p class="mt-2 text-sm leading-6 text-slate-500">Cette action garde votre session actuelle ouverte, mais déconnecte les autres appareils après confirmation de votre mot de passe.</p>
        </div>

        <div class="mt-5 flex flex-wrap items-center gap-3">
            <x-confirms-password wire:then="logoutOtherBrowserSessions">
                <x-button type="button" wire:loading.attr="disabled">Déconnecter les autres sessions</x-button>
            </x-confirms-password>
            <x-action-message class="text-sm font-bold text-emerald-700" on="loggedOut">Sessions déconnectées.</x-action-message>
        </div>
    </x-slot>
</x-action-section>

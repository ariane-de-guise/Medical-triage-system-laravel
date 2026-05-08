<x-form-section submit="updatePassword">
    <x-slot name="title">Mot de passe</x-slot>
    <x-slot name="description">Utilisez un mot de passe long et unique pour sécuriser votre accès au portail.</x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-label for="current_password" value="Mot de passe actuel" />
            <x-input id="current_password" type="password" wire:model="state.current_password" autocomplete="current-password" />
            <x-input-error for="current_password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="password" value="Nouveau mot de passe" />
            <x-input id="password" type="password" wire:model="state.password" autocomplete="new-password" />
            <x-input-error for="password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="password_confirmation" value="Confirmer le mot de passe" />
            <x-input id="password_confirmation" type="password" wire:model="state.password_confirmation" autocomplete="new-password" />
            <x-input-error for="password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3 text-sm font-bold text-emerald-700" on="saved">Enregistré.</x-action-message>
        <x-button>Mettre à jour</x-button>
    </x-slot>
</x-form-section>

<x-action-section>
    <x-slot name="title">Authentification à deux facteurs</x-slot>
    <x-slot name="description">Ajoutez une protection supplémentaire à votre compte avec un code de vérification sur téléphone.</x-slot>

    <x-slot name="content">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <div class="inline-flex rounded-full {{ $this->enabled ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }} px-4 py-2 text-xs font-black uppercase tracking-[0.18em]">
                    {{ $this->enabled ? 'A2F activée' : 'A2F désactivée' }}
                </div>
                <h3 class="mt-4 text-2xl font-black text-slate-900">
                    @if ($this->enabled)
                        @if ($showingConfirmation)
                            Terminer l’activation de l’A2F
                        @else
                            Votre compte est protégé
                        @endif
                    @else
                        Activez l’A2F pour plus de sécurité
                    @endif
                </h3>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500">
                    Une fois activée, l’application vous demandera un code temporaire généré par Google Authenticator, Microsoft Authenticator ou une application équivalente.
                </p>
            </div>
            <div class="rounded-3xl bg-blue-50 p-5 text-[#075fae]">
                <div class="text-3xl font-black">2FA</div>
                <div class="text-xs font-bold uppercase tracking-[0.2em]">Sécurité</div>
            </div>
        </div>

        @if ($this->enabled)
            @if ($showingQrCode)
                <div class="mt-6 rounded-3xl border border-blue-100 bg-blue-50/60 p-5">
                    <p class="font-bold text-slate-800">
                        @if ($showingConfirmation)
                            Scannez le code QR, puis entrez le code généré pour confirmer l’activation.
                        @else
                            Scannez ce code QR ou utilisez la clé de configuration ci-dessous.
                        @endif
                    </p>

                    <div class="mt-5 flex flex-col gap-5 md:flex-row md:items-center">
                        <div class="inline-block rounded-3xl bg-white p-4 shadow-lg">
                            {!! $this->user->twoFactorQrCodeSvg() !!}
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Clé de configuration</p>
                            <p class="mt-2 break-all rounded-2xl bg-white px-4 py-3 font-mono text-sm text-slate-700 shadow-sm">{{ decrypt($this->user->two_factor_secret) }}</p>
                        </div>
                    </div>

                    @if ($showingConfirmation)
                        <div class="mt-5 max-w-sm">
                            <x-label for="code" value="Code de vérification" />
                            <x-input id="code" type="text" name="code" inputmode="numeric" autofocus autocomplete="one-time-code" wire:model="code" wire:keydown.enter="confirmTwoFactorAuthentication" />
                            <x-input-error for="code" class="mt-2" />
                        </div>
                    @endif
                </div>
            @endif

            @if ($showingRecoveryCodes)
                <div class="mt-6 rounded-3xl border border-amber-100 bg-amber-50 p-5">
                    <p class="font-bold text-amber-900">Conservez ces codes dans un endroit sûr. Ils permettent de récupérer l’accès au compte si le téléphone est perdu.</p>
                    <div class="mt-4 grid gap-2 rounded-2xl bg-white p-4 font-mono text-sm text-slate-700 shadow-sm">
                        @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                            <div>{{ $code }}</div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        <div class="mt-6 flex flex-wrap gap-3">
            @if (! $this->enabled)
                <x-confirms-password wire:then="enableTwoFactorAuthentication">
                    <x-button type="button" wire:loading.attr="disabled">Activer l’A2F</x-button>
                </x-confirms-password>
            @else
                @if ($showingRecoveryCodes)
                    <x-confirms-password wire:then="regenerateRecoveryCodes"><x-secondary-button>Régénérer les codes</x-secondary-button></x-confirms-password>
                @elseif ($showingConfirmation)
                    <x-confirms-password wire:then="confirmTwoFactorAuthentication"><x-button type="button" wire:loading.attr="disabled">Confirmer</x-button></x-confirms-password>
                @else
                    <x-confirms-password wire:then="showRecoveryCodes"><x-secondary-button>Afficher les codes de récupération</x-secondary-button></x-confirms-password>
                @endif

                @if ($showingConfirmation)
                    <x-confirms-password wire:then="disableTwoFactorAuthentication"><x-secondary-button wire:loading.attr="disabled">Annuler</x-secondary-button></x-confirms-password>
                @else
                    <x-confirms-password wire:then="disableTwoFactorAuthentication"><x-danger-button wire:loading.attr="disabled">Désactiver</x-danger-button></x-confirms-password>
                @endif
            @endif
        </div>
    </x-slot>
</x-action-section>

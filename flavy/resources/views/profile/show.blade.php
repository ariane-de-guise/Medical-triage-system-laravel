<x-app-layout>
    <div class="space-y-8">
        <div
            class="relative overflow-hidden rounded-[2rem] bg-gradient-to-r from-[#062d58] via-[#075fae] to-[#15a6c8] dark:from-slate-900 dark:via-slate-900 dark:to-slate-800 p-8 text-white dark:text-white shadow-2xl shadow-blue-950/20">
            <div
                class="absolute right-0 top-0 h-full w-1/2 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,.35),transparent_45%)] dark:bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,.15),transparent_45%)]"></div>
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm font-black uppercase tracking-[0.35em] text-cyan-100 dark:text-cyan-100">Espace
                        sécurisé</p>
                    <h1 class="mt-3 text-4xl font-black tracking-tight">Mon profil</h1>
                    <p class="mt-3 max-w-2xl text-blue-50 dark:text-blue-100">Gérez vos informations personnelles, votre
                        mot de passe, l’authentification à deux facteurs et vos sessions actives.</p>
                </div>
                <div class="flex items-center gap-4 rounded-3xl bg-white/15 dark:bg-white/5 p-4 backdrop-blur">
                    <img src="{{ auth()->user()->profile_photo_url }}"
                         class="h-16 w-16 rounded-2xl bg-white object-cover ring-4 ring-white/30 dark:ring-white/10"
                         alt="Photo de profil">
                    <div>
                        <p class="text-lg font-black dark:text-white">{{ auth()->user()->compte?->prenom ?? auth()->user()->name }} {{ auth()->user()->compte?->nom ?? '' }}</p>
                        <p class="text-sm text-cyan-100 dark:text-cyan-100">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
            <div class="hospital-card p-5">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Compte</p>
                <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">Informations</p>
            </div>
            <div class="hospital-card p-5">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Sécurité</p>
                <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">Mot de passe</p>
            </div>
            <div class="hospital-card p-5">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
                    Protection</p>
                <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">A2F</p>
            </div>
        </div>
        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
            @livewire('profile.custom-update-profile-information-form', ['user' => auth()->user()])
            <x-section-border/>
        @endif
        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
            @livewire('profile.update-password-form')
            <x-section-border/>
        @endif
        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            @livewire('profile.two-factor-authentication-form')
            <x-section-border/>
        @endif
        @livewire('profile.logout-other-browser-sessions-form')
        @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
            <x-section-border/>
            @livewire('profile.delete-user-form')
        @endif
    </div>
</x-app-layout>

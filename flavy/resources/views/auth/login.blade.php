<x-guest-layout>
    <div class="grid min-h-screen lg:grid-cols-2 bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-white">
        <section
            class="hidden lg:flex flex-col justify-between bg-gradient-to-br from-blue-950 via-blue-800 to-cyan-600 p-12 text-white dark:from-slate-950 dark:via-slate-900 dark:to-slate-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-2xl bg-white p-2 dark:bg-slate-800">
                        <span x-show="!darkMode" x-cloak>
                            <img alt="CTHQ" src="{{ asset('logo/CTHQ.png') }}" class="h-full w-full object-contain"/>
                        </span>
                        <span x-show="darkMode" x-cloak>
                            <img alt="CTHQ" src="{{ asset('logo/CTHQ-dark.png') }}"
                                 class="h-full w-full object-contain"/>
                        </span>
                    </div>
                    <h1 class="text-2xl font-black">CTHQ</h1>
                </div>
                <button
                    @click="darkMode = !darkMode; localStorage.theme = darkMode ? 'dark' : 'light';"
                    class="relative flex items-center justify-center w-10 h-10 rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:border-cyan-300 hover:bg-cyan-50 dark:border-slate-700 dark:bg-slate-900 dark:text-yellow-300 dark:hover:bg-slate-800">
                    <span x-show="!darkMode" x-cloak>🌙</span>
                    <span x-show="darkMode" x-cloak>☀️</span>
                </button>
            </div>
            <div>
                <p class="text-sm font-bold uppercase tracking-[.3em] text-cyan-100">Portail clinique</p>
                <h2 class="mt-5 text-6xl font-black leading-tight">Connexion sécurisée</h2>
                <p class="mt-5 max-w-xl text-blue-50 dark:text-slate-300">
                    Accédez à votre espace patient, médecin, infirmier ou administrateur.
                </p>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div class="rounded-3xl bg-white/15 p-5">
                    <p class="text-2xl font-black">24/7</p>
                    <p class="text-sm text-cyan-100">Suivi</p>
                </div>
                <div class="rounded-3xl bg-white/15 p-5">
                    <p class="text-2xl font-black">A2F</p>
                    <p class="text-sm text-cyan-100">Sécurité</p>
                </div>
                <div class="rounded-3xl bg-white/15 p-5">
                    <p class="text-2xl font-black">CTHQ</p>
                    <p class="text-sm text-cyan-100">Réseau</p>
                </div>
            </div>
        </section>
        <section class="flex items-center justify-center p-6">
            <div class="w-full max-w-md hospital-card p-8 dark:bg-slate-900 dark:border dark:border-slate-800">
                <div class="mb-8">
                    <p class="hospital-badge">Bienvenue</p>
                    <h2 class="mt-4 text-4xl font-black">Se connecter</h2>
                    <p class="mt-2 text-slate-500 dark:text-slate-400">
                        Entrez vos informations pour continuer.
                    </p>
                </div>
                <x-validation-errors class="mb-4"/>
                @session('status')
                <div
                    class="mb-4 rounded-2xl bg-green-50 p-4 text-sm font-bold text-green-700 dark:bg-green-900/30 dark:text-green-300">
                    {{ $value }}
                </div>
                @endsession
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div>
                        <x-label for="email" value="Email" class="hospital-label"/>
                        <x-input id="email"
                                 class="hospital-input dark:bg-slate-800 dark:border-slate-700 dark:text-white"
                                 type="email" name="email" :value="old('email')" required autofocus/>
                    </div>
                    <div>
                        <x-label for="password" value="Mot de passe" class="hospital-label"/>
                        <x-input id="password"
                                 class="hospital-input dark:bg-slate-800 dark:border-slate-700 dark:text-white"
                                 type="password" name="password" required/>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                        <x-checkbox id="remember_me" name="remember"/>
                        <span>Se souvenir de moi</span>
                    </label>
                    <div class="flex items-center justify-between gap-4">
                        @if (Route::has('password.request'))
                            <a class="text-sm font-bold text-blue-700 hover:text-blue-900 dark:text-cyan-400"
                               href="{{ route('password.request') }}">
                                Mot de passe oublié?
                            </a>
                        @endif
                        <button class="hospital-btn">Connexion</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</x-guest-layout>

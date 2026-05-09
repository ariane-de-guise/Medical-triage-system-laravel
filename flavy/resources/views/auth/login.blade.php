<x-guest-layout>
<div class="grid min-h-screen lg:grid-cols-2">
    <section class="hidden lg:flex flex-col justify-between bg-gradient-to-br from-blue-950 via-blue-800 to-cyan-600 p-12 text-white">
        <div class="flex items-center gap-3">
            <div class="h-12 w-12 rounded-2xl bg-white p-2">
                <img src="{{ asset('logo/CTHQ.png') }}" class="h-full w-full object-contain">
            </div>
            <h1 class="text-2xl font-black">CTHQ</h1>
        </div>
        <div>
            <p class="text-sm font-bold uppercase tracking-[.3em] text-cyan-100">Portail clinique</p>
            <h2 class="mt-5 text-6xl font-black leading-tight">Connexion sécurisée</h2>
            <p class="mt-5 max-w-xl text-blue-50">Accédez à votre espace patient, médecin, infirmier ou administrateur.</p>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <div class="rounded-3xl bg-white/15 p-5">
                <p class="text-2xl font-black">24/7</p>
                <p class="text-sm text-cyan-100">Suivi</p>
            </div><div class="rounded-3xl bg-white/15 p-5">
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
        <div class="w-full max-w-md hospital-card p-8">
            <div class="mb-8">
                <p class="hospital-badge">Bienvenue</p>
                <h2 class="mt-4 text-4xl font-black">Se connecter</h2>
                <p class="mt-2 text-slate-500">Entrez vos informations pour continuer.</p>
            </div>
            <x-validation-errors class="mb-4" />
            @session('status')
            <div class="mb-4 rounded-2xl bg-green-50 p-4 text-sm font-bold text-green-700">{{ $value }}
            </div>
            @endsession
            <form method="POST" action="{{ route('login') }}" class="space-y-5">@csrf
                <div>
                    <x-label for="email" value="Email" class="hospital-label"/>
                    <x-input id="email" class="hospital-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>
                <div>
                    <x-label for="password" value="Mot de passe" class="hospital-label"/>
                    <x-input id="password" class="hospital-input" type="password" name="password" required autocomplete="current-password" />
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <x-checkbox id="remember_me" name="remember"/>
                    <span>Se souvenir de moi</span>
                </label>
                <div class="flex items-center justify-between gap-4">
                    @if (Route::has('password.request'))
                        <a class="text-sm font-bold text-blue-700 hover:text-blue-900" href="{{ route('password.request') }}">Mot de passe oublié?</a>
                    @endif
                        <button class="hospital-btn">Connexion</button>
                </div>
            </form>
        </div>
    </section>
</div>
</x-guest-layout>

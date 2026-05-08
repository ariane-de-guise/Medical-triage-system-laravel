<x-guest-layout>
<div class="grid min-h-screen lg:grid-cols-[.9fr_1.1fr]">
    <section class="hidden lg:flex flex-col justify-between bg-gradient-to-br from-blue-950 via-blue-800 to-cyan-600 p-12 text-white">
        <div class="flex items-center gap-3"><div class="h-12 w-12 rounded-2xl bg-white p-2"><img src="{{ asset('logo/CTHQ.png') }}" class="h-full w-full object-contain"></div><h1 class="text-2xl font-black">CTHQ</h1></div>
        <img src="{{ asset('images/hospital/register-care.svg') }}" class="mx-auto max-h-[430px] w-full max-w-md drop-shadow-2xl" alt="Inscription médicale">
        <div><p class="text-sm font-bold uppercase tracking-[.3em] text-cyan-100">Nouveau dossier</p><h2 class="mt-4 text-5xl font-black leading-tight">Créer un accès propre et sécurisé</h2></div>
    </section>
    <section class="overflow-y-auto p-6 lg:p-10">
        <div class="mx-auto max-w-3xl hospital-card p-8">
            <div class="mb-8"><p class="hospital-badge">Inscription</p><h2 class="mt-4 text-4xl font-black">Créer un utilisateur</h2><p class="mt-2 text-slate-500">Les champs restent identiques au projet de base, sans changer le contrôleur.</p></div>
            <x-validation-errors class="mb-4" />
            <form method="POST" action="{{ route('register') }}" class="grid gap-5 md:grid-cols-2">@csrf
                <div class="md:col-span-2"><x-label for="email" value="Email" class="hospital-label"/><x-input id="email" type="email" name="email" :value="old('email')" class="hospital-input" required /></div>
                <div><x-label for="password" value="Mot de passe" class="hospital-label"/><x-input id="password" type="password" name="password" class="hospital-input" required /></div>
                <div><x-label for="password_confirmation" value="Confirmation" class="hospital-label"/><x-input id="password_confirmation" type="password" name="password_confirmation" class="hospital-input" required /></div>
                <div><x-label for="nom" value="Nom" class="hospital-label"/><x-input id="nom" type="text" name="nom" :value="old('nom')" class="hospital-input" required /></div>
                <div><x-label for="prenom" value="Prénom" class="hospital-label"/><x-input id="prenom" type="text" name="prenom" :value="old('prenom')" class="hospital-input" required /></div>
                <div><x-label for="date_naissance" value="Date de naissance" class="hospital-label"/><x-input id="date_naissance" type="date" max="{{ now()->format('Y-m-d') }}" name="date_naissance" :value="old('date_naissance')" class="hospital-input" required /></div>
                <div><x-label for="telephone" value="Téléphone" class="hospital-label"/><x-input id="telephone" type="text" name="telephone" :value="old('telephone')" class="hospital-input" /></div>
                <div><x-label for="numero" value="Numéro" class="hospital-label"/><x-input id="numero" type="number" name="numero" :value="old('numero')" class="hospital-input" /></div>
                <div><x-label for="rue" value="Rue" class="hospital-label"/><x-input id="rue" type="text" name="rue" :value="old('rue')" class="hospital-input" /></div>
                <div><x-label for="ville" value="Ville" class="hospital-label"/><x-input id="ville" type="text" name="ville" :value="old('ville')" class="hospital-input" /></div>
                <div><x-label for="province" value="Province" class="hospital-label"/><x-input id="province" type="text" name="province" :value="old('province')" class="hospital-input" /></div>
                <div><x-label for="code_postal" value="Code postal" class="hospital-label"/><x-input id="code_postal" type="text" name="code_postal" :value="old('code_postal')" class="hospital-input" /></div>
                <div><x-label for="type" value="Type d’adresse" class="hospital-label"/><select id="type" name="type" class="hospital-input"><option value="maison" {{ old('type') == 'maison' ? 'selected' : '' }}>Maison</option><option value="travail" {{ old('type') == 'travail' ? 'selected' : '' }}>Travail</option></select></div>
                @if(auth()->check() && auth()->user()->hasRole('admin'))<div class="md:col-span-2"><x-label for="role" value="Rôle" class="hospital-label"/><select name="role" id="role" class="hospital-input"><option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option><option value="infirmier" {{ old('role') == 'infirmier' ? 'selected' : '' }}>Infirmier</option><option value="medecin" {{ old('role') == 'medecin' ? 'selected' : '' }}>Médecin</option><option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option></select></div>@endif
                <div class="md:col-span-2 flex justify-end"><button class="hospital-btn">Créer le compte</button></div>
            </form>
        </div>
    </section>
</div>
</x-guest-layout>

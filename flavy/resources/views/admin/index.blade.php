<x-app-layout>

    <x-slot name="header">
        <div>
            <p class="hospital-badge">Administration</p>
            <h2 class="mt-3 text-4xl font-black text-slate-950">Centre de contrôle</h2>
            <p class="mt-2 text-slate-500">Gestion des comptes, accès et rôles.</p>
        </div>
    </x-slot>

    <div class="grid gap-6 lg:grid-cols-3">
        <a href="{{ route('register') }}" class="hospital-card p-7 transition hover:-translate-y-1">
            <div class="grid h-14 w-14 place-items-center rounded-2xl bg-blue-100 text-2xl">＋</div>
            <h3 class="mt-5 text-2xl font-black">Créer un utilisateur</h3>
            <p class="mt-2 text-slate-500">Ajouter un patient, médecin, infirmier ou admin.</p>
        </a>

        <a href="{{ route('admin.roles') }}"
           class="hospital-card p-7 transition hover:-translate-y-1">
            <div class="grid h-14 w-14 place-items-center rounded-2xl bg-cyan-100 text-2xl">⚙</div>
            <h3 class="mt-5 text-2xl font-black">Gérer les rôles</h3>
            <p class="mt-2 text-slate-500">Ajouter ou retirer les permissions.</p>
        </a>

        <a href="{{ route('patients.liste') }}" class="hospital-card p-7 transition hover:-translate-y-1">
            <div class="grid h-14 w-14 place-items-center rounded-2xl bg-blue-100 text-2xl">☷</div>
            <h3 class="mt-5 text-2xl font-black">Liste des patients</h3>
            <p class="mt-2 text-slate-500">Voir les patients enregistrés et leurs anciennes visites.</p>
        </a>

        <div class="rounded-3xl bg-gradient-to-br from-blue-950 to-cyan-600 p-7 text-white shadow-2xl">
            <p class="font-bold text-cyan-100">Sécurité</p>
            <h3 class="mt-3 text-3xl font-black">Accès admin seulement</h3>
            <p class="mt-3 text-blue-50">Cette section est protégée par les rôles Laravel.</p>
        </div>
    </div>
</x-app-layout>

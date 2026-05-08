<x-app-layout>
    @php
        $compte = $patient->compte;
        $visites = $compte?->visites()->with(['docteur', 'diagnostique'])->latest('date_heure')->get() ?? collect();
        $initiales = strtoupper(substr($compte->prenom ?? $patient->name ?? 'P', 0, 1) . substr($compte->nom ?? '', 0, 1));
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="hospital-badge">Dossier patient</p>
                <h2 class="mt-3 text-4xl font-black text-slate-950">{{ $compte->prenom ?? 'Patient' }} {{ $compte->nom ?? '' }}</h2>
                <p class="mt-2 max-w-2xl text-slate-500">Informations du patient et historique des anciennes visites.</p>
            </div>
            <a href="{{ route('patients.liste') }}" class="hospital-btn-soft inline-flex items-center justify-center">← Retour à la liste</a>
        </div>
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-[.9fr_1.1fr]">
        <section class="space-y-6">
            <div class="rounded-[2rem] bg-gradient-to-br from-blue-950 via-blue-900 to-cyan-700 p-8 text-white shadow-2xl">
                <div class="flex items-center gap-5">
                    <div class="grid h-24 w-24 place-items-center rounded-[1.7rem] bg-white text-3xl font-black text-blue-950 shadow-xl">{{ $initiales }}</div>
                    <div>
                        <p class="text-cyan-100 font-bold">Patient enregistré</p>
                        <h3 class="mt-1 text-3xl font-black">{{ $compte->prenom ?? 'Prénom inconnu' }} {{ $compte->nom ?? 'Nom inconnu' }}</h3>
                        <p class="mt-1 text-blue-50">{{ $patient->email }}</p>
                    </div>
                </div>
            </div>

            <div class="hospital-card p-7">
                <h3 class="text-2xl font-black text-slate-950">Informations personnelles</h3>
                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Nom complet</p>
                        <p class="mt-1 font-bold text-slate-800">{{ $compte->prenom ?? 'Non renseigné' }} {{ $compte->nom ?? '' }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Date de naissance</p>
                        <p class="mt-1 font-bold text-slate-800">{{ $compte?->date_naissance ? \Carbon\Carbon::parse($compte->date_naissance)->format('d/m/Y') : 'Non renseignée' }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Courriel</p>
                        <p class="mt-1 font-bold text-slate-800">{{ $patient->email }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Compte créé</p>
                        <p class="mt-1 font-bold text-slate-800">{{ $patient->created_at ? $patient->created_at->format('d/m/Y H:i') : 'Non disponible' }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="hospital-card overflow-hidden">
            <div class="flex flex-col gap-3 border-b border-slate-100 p-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-2xl font-black text-slate-950">Anciennes visites</h3>
                    <p class="mt-1 text-sm text-slate-500">Historique clinique associé au patient.</p>
                </div>
                <span class="hospital-badge">{{ $visites->count() }} visite(s)</span>
            </div>

            @if($visites->isEmpty())
                <div class="p-10 text-center">
                    <div class="mx-auto grid h-20 w-20 place-items-center rounded-3xl bg-blue-50 text-4xl">🗂</div>
                    <h4 class="mt-5 text-2xl font-black text-slate-900">Aucune visite</h4>
                    <p class="mt-2 text-slate-500">Ce patient n’a pas encore d’historique de visite enregistré.</p>
                </div>
            @else
                <div class="space-y-4 p-6">
                    @foreach($visites as $visite)
                        <article class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Date de visite</p>
                                    <h4 class="mt-1 text-xl font-black text-slate-950">{{ $visite->date_heure ? \Carbon\Carbon::parse($visite->date_heure)->format('d/m/Y à H:i') : 'Date inconnue' }}</h4>
                                </div>
                                <span class="hospital-badge">Visite #{{ $visite->id }}</span>
                            </div>

                            <div class="mt-5 grid gap-4 md:grid-cols-2">
                                <div class="rounded-2xl bg-white p-4 shadow-sm">
                                    <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Médecin</p>
                                    <p class="mt-1 font-bold text-slate-800">{{ $visite->docteur?->prenom ?? 'Non assigné' }} {{ $visite->docteur?->nom ?? '' }}</p>
                                </div>
                                <div class="rounded-2xl bg-white p-4 shadow-sm">
                                    <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Diagnostic</p>
                                    <p class="mt-1 font-bold text-slate-800">{{ ($visite->diagnostique?->description && $visite->diagnostique->description !== 'À remplir') ? $visite->diagnostique->description : 'Non renseigné' }}</p>
                                </div>
                            </div>

                            @if($visite->diagnostique?->commentaire)
                                <div class="mt-4 rounded-2xl bg-white p-4 shadow-sm">
                                    <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Commentaire infirmière</p>
                                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $visite->diagnostique->commentaire }}</p>
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="hospital-badge">Patients</p>
                <h2 class="mt-3 text-4xl font-black text-slate-950">Liste des patients</h2>
                <p class="mt-2 max-w-2xl text-slate-500">Consultez tous les patients enregistrés dans la base de données et accédez rapidement à leur dossier.</p>
            </div>
            <div class="hospital-card px-5 py-4">
                <p class="text-xs font-black uppercase tracking-[0.25em] text-slate-400">Total</p>
                <p class="mt-1 text-3xl font-black text-blue-950">{{ $patients->count() }}</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="rounded-[2rem] bg-gradient-to-br from-blue-950 via-blue-900 to-cyan-700 p-8 text-white shadow-2xl">
            <div class="grid gap-6 lg:grid-cols-[1.4fr_.6fr] lg:items-center">
                <div>
                    <p class="text-sm font-black uppercase tracking-[0.3em] text-cyan-100">Dossier clinique</p>
                    <h3 class="mt-3 text-3xl font-black">Vue rapide des patients</h3>
                    <p class="mt-3 max-w-2xl text-blue-50">Cette section est réservée aux médecins et aux administrateurs pour consulter les informations principales et les anciennes visites.</p>
                </div>
                <div class="rounded-3xl bg-white/10 p-5 backdrop-blur border border-white/10">
                    <p class="text-cyan-100 text-sm font-bold">Accès sécurisé</p>
                    <p class="mt-2 text-2xl font-black">Admin / Médecin</p>
                </div>
            </div>
        </section>

        <section class="hospital-card overflow-hidden">
            <div class="flex flex-col gap-3 border-b border-slate-100 p-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-2xl font-black text-slate-950">Patients enregistrés</h3>
                    <p class="mt-1 text-sm text-slate-500">Cliquez sur un dossier pour voir les détails du patient.</p>
                </div>
                <span class="hospital-badge">{{ $patients->count() }} patient(s)</span>
            </div>

            @if($patients->isEmpty())
                <div class="p-10 text-center">
                    <div class="mx-auto grid h-20 w-20 place-items-center rounded-3xl bg-blue-50 text-4xl">♡</div>
                    <h4 class="mt-5 text-2xl font-black text-slate-900">Aucun patient trouvé</h4>
                    <p class="mt-2 text-slate-500">Il n’y a pas encore de patient enregistré dans la base de données.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($patients as $patient)
                        @php
                            $compte = $patient->compte;
                            $visitesCount = $compte?->visites()->count() ?? 0;
                            $derniereVisite = $compte?->visites()->latest('date_heure')->first();
                        @endphp
                        <article class="group grid gap-5 p-6 transition hover:bg-slate-50 lg:grid-cols-[1.2fr_.8fr_.7fr_auto] lg:items-center">
                            <div class="flex items-center gap-4">
                                <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-gradient-to-br from-blue-900 to-cyan-500 text-lg font-black text-white shadow-lg">
                                    {{ strtoupper(substr($compte->prenom ?? $patient->name ?? 'P', 0, 1) . substr($compte->nom ?? '', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="text-lg font-black text-slate-950">{{ $compte->prenom ?? 'Prénom inconnu' }} {{ $compte->nom ?? 'Nom inconnu' }}</h4>
                                    <p class="mt-1 text-sm text-slate-500">{{ $patient->email }}</p>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Date de naissance</p>
                                <p class="mt-1 font-bold text-slate-700">{{ $compte?->date_naissance ? \Carbon\Carbon::parse($compte->date_naissance)->format('d/m/Y') : 'Non renseignée' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Historique</p>
                                <p class="mt-1 font-bold text-slate-700">{{ $visitesCount }} visite(s)</p>
                                <p class="text-xs text-slate-500">Dernière : {{ $derniereVisite?->date_heure ? \Carbon\Carbon::parse($derniereVisite->date_heure)->format('d/m/Y H:i') : 'Aucune' }}</p>
                            </div>

                            <div class="flex lg:justify-end">
                                <a href="{{ route('patients.show', $patient->id) }}" class="hospital-btn inline-flex items-center justify-center">Voir dossier</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</x-app-layout>

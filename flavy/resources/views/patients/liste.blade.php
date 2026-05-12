<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="hospital-badge">Patients</p>
                <h2 class="mt-3 text-4xl font-black text-slate-950 dark:text-white">Liste des patients</h2>
                <p class="mt-2 max-w-2xl text-slate-500 dark:text-slate-400">Consultez tous les patients enregistrés
                    dans la base de données et accédez rapidement à leur dossier.</p>
            </div>
            <div class="hospital-card px-5 py-4 dark:bg-slate-900">
                <p class="text-xs font-black uppercase tracking-[0.25em] text-slate-400 dark:text-slate-500">Total</p>
                <p class="mt-1 text-3xl font-black text-blue-950 dark:text-blue-100">{{ $patients->count() }}</p>
            </div>
        </div>
    </x-slot>
    <div class="space-y-6">
        <section
            class="rounded-[2rem] bg-gradient-to-br from-blue-950 via-blue-900 to-cyan-700 dark:from-slate-900 dark:via-slate-900 dark:to-slate-800 p-8 text-white shadow-2xl">
            <div class="grid gap-6 lg:grid-cols-[1.4fr_.6fr] lg:items-center">
                <div>
                    <p class="text-sm font-black uppercase tracking-[0.3em] text-cyan-100">Dossier clinique</p>
                    <h3 class="mt-3 text-3xl font-black">Vue rapide des patients</h3>
                    <p class="mt-3 max-w-2xl text-blue-50">Cette section est réservée aux médecins et aux
                        administrateurs pour consulter les informations principales et les anciennes visites.</p>
                </div>
                <div
                    class="rounded-3xl bg-white/10 dark:bg-white/5 p-5 backdrop-blur border border-white/10 dark:border-white/10">
                    <p class="text-cyan-100 text-sm font-bold">Accès sécurisé</p>
                    <p class="mt-2 text-2xl font-black">Admin / Médecin</p>
                </div>
            </div>
        </section>
        <section class="hospital-card overflow-hidden dark:bg-slate-900">
            <div
                class="flex flex-col gap-3 border-b border-slate-100 dark:border-slate-800 p-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-2xl font-black text-slate-950 dark:text-white">Patients enregistrés</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Cliquez sur un dossier pour voir les
                        détails du patient.</p>
                </div>
                <span class="hospital-badge">{{ $patients->count() }} patient(s)</span>
            </div>
            @if($patients->isEmpty())
                <div class="p-10 text-center">
                    <div
                        class="mx-auto grid h-20 w-20 place-items-center rounded-3xl bg-blue-50 dark:bg-slate-800/50 text-4xl">
                        ♡
                    </div>
                    <h4 class="mt-5 text-2xl font-black text-slate-900 dark:text-white">Aucun patient trouvé</h4>
                    <p class="mt-2 text-slate-500 dark:text-slate-400">Il n’y a pas encore de patient enregistré dans la
                        base de données.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($patients as $patient)
                        <article
                            class="group grid gap-5 p-6 transition hover:bg-slate-50 dark:hover:bg-slate-800 lg:grid-cols-[1.2fr_.8fr_.7fr_auto] lg:items-center">
                            <div class="flex items-center gap-4">
                                <div
                                    class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-gradient-to-br from-blue-900 to-cyan-500 dark:from-slate-700 dark:to-slate-500 text-lg font-black text-white shadow-lg">
                                    {{ $patient['initiales'] }}
                                </div>
                                <div>
                                    <h4 class="text-lg font-black text-slate-950 dark:text-white">
                                        {{ $patient['prenom'] }} {{ $patient['nom'] }}
                                    </h4>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        {{ $patient['email'] }}
                                    </p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
                                    Date de naissance
                                </p>
                                <p class="mt-1 font-bold text-slate-700 dark:text-slate-300">
                                    {{ $patient['date_naissance'] }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
                                    Historique
                                </p>
                                <p class="mt-1 font-bold text-slate-700 dark:text-slate-300">
                                    {{ $patient['visites_count'] }} visite(s)
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    Dernière : {{ $patient['derniere_visite'] }}
                                </p>
                            </div>
                            <div class="flex lg:justify-end">
                                <a href="{{ route('patients.show', $patient['id']) }}"
                                   class="hospital-btn inline-flex items-center justify-center">
                                    Voir dossier
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</x-app-layout>

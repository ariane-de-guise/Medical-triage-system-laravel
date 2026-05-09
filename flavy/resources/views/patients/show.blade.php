<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="hospital-badge">Dossier patient</p>
                <h2 class="mt-3 text-4xl font-black text-slate-950 dark:text-white">
                    {{ $patient['prenom'] }} {{ $patient['nom'] }}
                </h2>
                <p class="mt-2 max-w-2xl text-slate-500 dark:text-slate-400">
                    Informations du patient et historique des anciennes visites.
                </p>
            </div>
            <a href="{{ route('patients.liste') }}" class="hospital-btn-soft inline-flex items-center justify-center">
                ← Retour à la liste
            </a>
        </div>
    </x-slot>
    <div class="grid gap-6 xl:grid-cols-[.9fr_1.1fr]">
        <section class="space-y-6">
            <div
                class="rounded-[2rem] bg-gradient-to-br from-blue-950 via-blue-900 to-cyan-700 dark:from-slate-900 dark:via-slate-900 dark:to-slate-800 p-8 text-white shadow-2xl">
                <div class="flex items-center gap-5">
                    <div
                        class="grid h-24 w-24 place-items-center rounded-[1.7rem] bg-white text-3xl font-black text-blue-950 shadow-xl">
                        {{ $patient['initiales'] }}
                    </div>
                    <div>
                        <p class="font-bold text-cyan-100">
                            Patient enregistré
                        </p>
                        <h3 class="mt-1 text-3xl font-black">
                            {{ $patient['prenom'] }} {{ $patient['nom'] }}
                        </h3>
                        <p class="mt-1 text-blue-50 dark:text-blue-100">
                            {{ $patient['email'] }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="hospital-card p-7 dark:bg-slate-900">
                <h3 class="text-2xl font-black text-slate-950 dark:text-white">
                    Informations personnelles
                </h3>
                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-4">
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
                            Nom complet
                        </p>
                        <p class="mt-1 font-bold text-slate-800 dark:text-slate-200">
                            {{ $patient['prenom'] }} {{ $patient['nom'] }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-4">
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
                            Date de naissance
                        </p>
                        <p class="mt-1 font-bold text-slate-800 dark:text-slate-200">
                            {{ $patient['date_naissance'] }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-4">
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
                            Courriel
                        </p>
                        <p class="mt-1 font-bold text-slate-800 dark:text-slate-200">
                            {{ $patient['email'] }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-4">
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
                            Compte créé
                        </p>
                        <p class="mt-1 font-bold text-slate-800 dark:text-slate-200">
                            {{ $patient['created_at'] }}
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <section class="hospital-card overflow-hidden dark:bg-slate-900">
            <div
                class="flex flex-col gap-3 border-b border-slate-100 dark:border-slate-800 p-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-2xl font-black text-slate-950 dark:text-white">
                        Anciennes visites
                    </h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Historique clinique associé au patient.
                    </p>
                </div>
                <span class="hospital-badge">
                    {{ $visites->count() }} visite(s)
                </span>
            </div>
            @if($visites->isEmpty())
                <div class="p-10 text-center">
                    <div
                        class="mx-auto grid h-20 w-20 place-items-center rounded-3xl bg-blue-50 dark:bg-slate-800 text-4xl">
                        🗂
                    </div>
                    <h4 class="mt-5 text-2xl font-black text-slate-900 dark:text-white">
                        Aucune visite
                    </h4>
                    <p class="mt-2 text-slate-500 dark:text-slate-400">
                        Ce patient n’a pas encore d’historique de visite enregistré.
                    </p>
                </div>
            @else
                <div class="space-y-4 p-6">
                    @foreach($visites as $visite)
                        <article
                            class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 p-5">
                            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
                                        Date de visite
                                    </p>
                                    <h4 class="mt-1 text-xl font-black text-slate-950 dark:text-white">
                                        {{ $visite['date_visite'] }}
                                    </h4>
                                </div>
                                <span class="hospital-badge">
                                    Visite #{{ $visite['id'] }}
                                </span>
                            </div>
                            <div class="mt-5 grid gap-4 md:grid-cols-2">
                                <div class="rounded-2xl bg-white dark:bg-slate-900 p-4 shadow-sm">
                                    <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
                                        Médecin
                                    </p>
                                    <p class="mt-1 font-bold text-slate-800 dark:text-slate-200">
                                        {{ $visite['medecin'] }}
                                    </p>
                                </div>
                                <div class="rounded-2xl bg-white dark:bg-slate-900 p-4 shadow-sm">
                                    <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
                                        Diagnostic
                                    </p>
                                    <p class="mt-1 font-bold text-slate-800 dark:text-slate-200">
                                        {{ $visite['diagnostic'] }}
                                    </p>
                                </div>
                            </div>
                            @if($visite['commentaire'])
                                <div class="mt-4 rounded-2xl bg-white dark:bg-slate-900 p-4 shadow-sm">
                                    <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
                                        Commentaire infirmière
                                    </p>
                                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                        {{ $visite['commentaire'] }}
                                    </p>
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</x-app-layout>

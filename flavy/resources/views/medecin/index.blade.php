<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="hospital-badge">Médecin</p>
            <h2 class="mt-3 text-4xl font-black text-slate-950">File de consultation</h2>
            <p class="mt-2 text-slate-500">Patients priorisés par le triage infirmier.</p>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 hospital-card border-green-200 bg-green-50 p-4 font-bold text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 hospital-card border-red-200 bg-red-50 p-4 font-bold text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 grid gap-4 lg:grid-cols-3">
        <a href="{{ route('patients.liste') }}" class="hospital-card p-6 hover:-translate-y-1 transition lg:col-span-1">
            <div class="h-14 w-14 rounded-2xl bg-blue-100 grid place-items-center text-2xl">☷</div>
            <h3 class="mt-5 text-2xl font-black text-slate-950">Tous les patients</h3>
            <p class="mt-2 text-slate-500">Consulter les dossiers et les anciennes visites.</p>
        </a>
    </div>

    <section class="hospital-card p-7">
        @if ($estMedecinActif)
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-2xl font-black">Vous êtes disponible</h3>
                    <p class="text-slate-500">Vous pouvez prendre le premier patient de la file.</p>
                </div>

                <form method="POST" action="{{ route('medecin.logout') }}">
                    @csrf
                    @method('DELETE')
                    <button class="hospital-btn-soft">Terminer les consultations</button>
                </form>
            </div>

            <div class="mt-8">
                <h4 class="text-xl font-black">Patients dans la file</h4>

                @if(count($patients) === 0)
                    <div class="mt-5 rounded-3xl border-2 border-dashed border-slate-200 p-10 text-center text-slate-500">
                        Aucun patient dans la file.
                    </div>
                @else
                    <div class="mt-5 space-y-4">
                        @foreach($patients as $patient)
                            <article class="rounded-3xl border border-slate-200 bg-slate-50 p-5">

                                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">

                                    <div>
                                        <h5 class="text-xl font-black">
                                            {{ $patient['compte']->prenom }}
                                            {{ $patient['compte']->nom }}
                                        </h5>

                                        <p class="mt-1 text-sm text-slate-500">
                                            Début :
                                            {{ $patient['date_visite'] ?? 'Non disponible' }}
                                        </p>
                                    </div>

                                    <span class="hospital-badge">Priorité {{ $patient['priorite'] ?? 'N/A' }}</span>

                                </div>

                                <p class="mt-4 text-sm text-slate-600">
                                    <strong class="text-slate-900">Commentaires infirmière :</strong>
                                    <br>
                                    {{ $patient['commentaire'] ?? 'Aucun commentaire' }}
                                </p>

                                <div class="mt-4 flex items-center justify-between">

                                    <p class="text-xs font-bold text-slate-400">
                                        Attente :
                                        @if($patient['attente'])
                                            {{ (int) $patient['attente'] }} minutes
                                        @else
                                            Non disponible
                                        @endif
                                    </p>

                                    @if ($patient['index'] === 0)
                                        <form method="GET" action="{{ route('medecin.show', $patient['compte']) }}">
                                            <button class="hospital-btn">
                                                Prendre en charge
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>

        @elseif ($estInfirmierActif)
            <div class="rounded-[2rem] border border-amber-200 bg-amber-50 p-8 text-amber-900">
                <div class="inline-flex rounded-2xl bg-amber-100 px-4 py-2 text-sm font-black">
                    Service déjà actif
                </div>

                <h3 class="mt-5 text-3xl font-black">
                    Vous êtes déjà en service comme infirmier
                </h3>

                <p class="mt-2 text-amber-800">
                    Vous ne pouvez pas être connecté comme médecin et infirmier en même temps.
                    Terminez d'abord votre service de triage.
                </p>

                <form method="POST" action="{{ route('infirmier.logout') }}" class="mt-6">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-2xl bg-amber-600 px-6 py-3 font-black text-white shadow-xl hover:bg-amber-700">
                        Terminer le service infirmier
                    </button>
                </form>
            </div>

        @else
            <div class="rounded-[2rem] bg-gradient-to-br from-blue-950 to-cyan-600 p-8 text-white">
                <h3 class="text-3xl font-black">Démarrer votre quart</h3>

                <p class="mt-2 text-blue-50">
                    Connectez-vous comme médecin disponible pour recevoir les consultations.
                </p>

                <form method="POST" action="{{ route('medecin.login') }}" class="mt-6">
                    @csrf
                    <button class="rounded-2xl bg-white px-6 py-3 font-black text-blue-950 shadow-xl">
                        Prêt à consulter
                    </button>
                </form>
            </div>
        @endif
    </section>
</x-app-layout>

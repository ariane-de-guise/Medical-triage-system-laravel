<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="hospital-badge">Infirmier</p>
            <h2 class="mt-3 text-4xl font-black text-slate-950">Triage des patients</h2>
            <p class="mt-2 text-slate-500">Évaluer les symptômes et envoyer vers la bonne file.</p>
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

    <section class="hospital-card p-7">
        @if ($estInfirmierActif)
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-2xl font-black">Poste de triage actif</h3>
                    <p class="text-slate-500">Prenez en charge le premier patient arrivé.</p>
                </div>

                <form method="POST" action="{{ route('infirmier.logout') }}">
                    @csrf
                    @method('DELETE')
                    <button class="hospital-btn-soft">Terminer le triage</button>
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
                        @foreach($patients as $i => $compte)
                            <article class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <h5 class="text-xl font-black">
                                            {{ $compte->prenom }} {{ $compte->nom }}
                                        </h5>

                                        <p class="mt-1 text-sm text-slate-500">
                                            Arrivée :
                                            {{ $compte->triage?->created_at ?? 'Non disponible' }}
                                        </p>
                                    </div>

                                    @if ($i === 0)
                                        <form method="GET" action="{{ route('infirmier.show', $compte) }}">
                                            <button class="hospital-btn">Prendre en charge</button>
                                        </form>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>

        @elseif ($estMedecinActif)
            <div class="rounded-[2rem] border border-amber-200 bg-amber-50 p-8 text-amber-900">
                <div class="inline-flex rounded-2xl bg-amber-100 px-4 py-2 text-sm font-black">
                    Service déjà actif
                </div>

                <h3 class="mt-5 text-3xl font-black">
                    Vous êtes déjà en service comme médecin
                </h3>

                <p class="mt-2 text-amber-800">
                    Vous ne pouvez pas être connecté comme infirmier et médecin en même temps.
                    Terminez d'abord votre service de consultation.
                </p>

                <form method="POST" action="{{ route('medecin.logout') }}" class="mt-6">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-2xl bg-amber-600 px-6 py-3 font-black text-white shadow-xl hover:bg-amber-700">
                        Terminer le service médecin
                    </button>
                </form>
            </div>

        @else
            <div class="rounded-[2rem] bg-gradient-to-br from-blue-950 to-cyan-600 p-8 text-white">
                <h3 class="text-3xl font-black">Ouvrir le poste de triage</h3>

                <p class="mt-2 text-blue-50">
                    Connectez-vous comme infirmier disponible.
                </p>

                <form method="POST" action="{{ route('infirmier.login') }}" class="mt-6">
                    @csrf
                    <button class="rounded-2xl bg-white px-6 py-3 font-black text-blue-950 shadow-xl">
                        Prêt à trier
                    </button>
                </form>
            </div>
        @endif
    </section>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="hospital-badge">Consultation infirmière</p>
            <h2 class="mt-3 text-4xl font-black text-slate-950 dark:text-white">{{ $compte->prenom }} {{ $compte->nom }}</h2>
        </div>
    </x-slot>
    <section class="hospital-card p-7 dark:bg-slate-900">
        @if (\App\Algorithms\TimeCard::loggedin(auth()->user(), \App\Algorithms\Roles::Infirmier))
            <div class="grid gap-5 lg:grid-cols-2">
                <div class="rounded-3xl bg-slate-50 dark:bg-slate-800 p-5">
                    <p class="text-sm font-bold text-slate-500 dark:text-slate-400">Arrivée</p>
                    <p class="mt-2 font-black text-slate-900 dark:text-white">{{ $compte->triage->created_at }}</p>
                </div>
                <div class="rounded-3xl bg-slate-50 dark:bg-slate-800 p-5">
                    <p class="text-sm font-bold text-slate-500 dark:text-slate-400">Date de naissance</p>
                    <p class="mt-2 font-black text-slate-900 dark:text-white">{{ $compte->date_naissance }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('infirmier.next', $compte) }}" class="mt-6">
                @csrf
                <input type="hidden" name="symptomes" value="{{ $symptomes }}">
                @if ($priorite !== null)
                    <div class="grid gap-5 lg:grid-cols-[.4fr_1fr]">
                        <div>
                            <label for="priorite" class="hospital-label">Priorité</label>
                            <input id="priorite" type="number" name="priorite"
                                   class="hospital-input text-3xl font-black"
                                   value="{{ $priorite['priorite'] }}">
                        </div>
                        <div>
                            <label for="commentaire" class="hospital-label">Commentaire</label>
                            <textarea id="commentaire" name="commentaire"
                                      class="hospital-input dark:bg-slate-800 dark:text-white"
                                      rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="mt-6">
                        <p class="hospital-label">Afflictions possibles</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($priorite['afflictions'] as $affliction)
                                <span class="hospital-badge">{{ ucfirst($affliction['nom']) }}</span>
                            @endforeach
                        </div>
                    </div>
                    <button class="hospital-btn mt-6">Terminer la consultation</button>
                @endif
            </form>
            @if ($priorite == null)
                <form method="GET" action="{{ route('infirmier.show', $compte) }}" class="mt-6">
                    <p class="mb-4 text-lg font-black dark:text-white">Symptômes observés</p>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                        @foreach(App\Algorithms\Symptomes::cases() as $symptome)
                            <label
                                class="flex items-center gap-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-3 font-semibold text-slate-700 dark:text-slate-200">
                                <x-checkbox id="{{ $symptome->value }}" name="symptomes[]"
                                            value="{{ $symptome->value }}"/>
                                <span>{{ mb_convert_case($symptome->value, MB_CASE_TITLE, 'UTF-8') }}</span>
                            </label>
                        @endforeach
                    </div>
                    <button class="hospital-btn mt-6">Calculer la priorité</button>
                </form>
            @endif
        @else
            <div
                class="rounded-3xl bg-slate-50 dark:bg-slate-900 p-8 text-center font-bold text-slate-600 dark:text-slate-400">
                Vous n'êtes pas connecté.
            </div>
        @endif
        <div class="mt-6">
            <a href="{{ route('infirmier') }}" class="hospital-btn-soft">
                Retour à la file d'attente
            </a>
        </div>
    </section>
</x-app-layout>

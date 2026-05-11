<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="hospital-badge">Consultation médecin</p>
            <h2 class="mt-3 text-4xl font-black text-slate-950 dark:text-white">{{ $compte->prenom }} {{ $compte->nom }}</h2>
        </div>
    </x-slot>
    <section class="hospital-card p-7 dark:bg-slate-900">
        @if (\App\Algorithms\TimeCard::loggedin(auth()->user(), \App\Algorithms\Roles::Medecin))
            <form method="POST" action="{{ route('medecin.next', $compte) }}">
                @csrf
                @method('PATCH')
                <div class="grid gap-5 lg:grid-cols-3">
                    <div class="rounded-3xl bg-slate-50 dark:bg-slate-800 p-5">
                        <p class="text-sm font-bold text-slate-500 dark:text-slate-400">Début de visite</p>
                        <p class="mt-2 font-black text-slate-900 dark:text-white">
                            {{ $compte->visites()->latest('date_heure')->value('date_heure') }}
                        </p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 dark:bg-slate-800 p-5 lg:col-span-2">
                        <p class="text-sm font-bold text-slate-500 dark:text-slate-400">Commentaires infirmière</p>
                        <p class="mt-2 text-slate-700 dark:text-slate-300">
                            {{ $compte->visites()->latest('date_heure')->first()?->diagnostique->commentaire }}
                        </p>
                    </div>
                </div>
                <div class="mt-6">
                    <x-label for="diagnostique" value="Diagnostic / traitement" class="hospital-label"/>
                    <textarea id="diagnostique" name="diagnostique" rows="6" class="hospital-input" required></textarea>
                </div>
                <div class="mt-6 flex gap-3">
                    <button class="hospital-btn">Terminer la consultation</button>
                    <a href="{{ route('medecin') }}" class="hospital-btn-soft">Retour</a>
                </div>
            </form>
        @else
            <div
                class="rounded-3xl bg-slate-50 dark:bg-slate-900 p-8 text-center font-bold text-slate-600 dark:text-slate-400">
                Vous n'êtes pas connecté.
            </div>
        @endif
    </section>
</x-app-layout>

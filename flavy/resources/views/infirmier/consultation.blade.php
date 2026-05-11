<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Espace Infirmier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-yellow-100 p-6 rounded shadow">
                <h3 class="text-lg font-medium mb-2">Espace Infirmier</h3>
                @if (\App\Algorithms\TimeCard::loggedin(auth()->user(), \App\Algorithms\Roles::Infirmier))
                    <form method="POST" action="{{ route('infirmier.next', $compte) }}">
                        @csrf
                        <input type="hidden" name="symptomes" value="{{ $symptomes }}">
                        <hr class="my-6 border-gray-300">
                        <h2 class="text-md font-medium mb-2">{{ $compte->prenom }} {{ $compte->nom }}</h2>
                        <br>
                        <p><span class="font-semibold">Date de début de la visite :</span><br>
                            {{ $compte->triage->created_at }}
                        </p>
                        <br>
                        <p><span class="font-semibold">Date de naissance :</span><br>
                            {{ $compte->date_naissance }}
                        </p>
                        <br>
                        @if ($priorite !== null)
                            <label for="priorite" class="font-semibold">Priorité :</label><br>
                            <input type="number" name="priorite" class="text-sm px-3 py-1 rounded-full bg-yellow-100 text-yellow-700" value="{{ $priorite['priorite'] }}">
                            <br><br>
                            <label for="commentaire" class="font-semibold">Commentaire :</label><br>
                            <textarea id="commentaire" name="commentaire" class="mt-1 block w-full border-gray-300 rounded-md" rows="4"></textarea>
                            <br><br>
                            <span class="font-semibold">Afflictions possibles</span>
                            @foreach($priorite['afflictions'] as $affliction)
                                <span class="inline-block mt-1 px-3 py-1 rounded-full bg-yellow-100 text-yellow-700">
                                    {{ ucfirst($affliction['nom']) }}
                                </span>
                            @endforeach
                        @endif
                        @if ($priorite !== null)
                            <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-yellow-200 text-yellow-700 rounded">
                                Terminer la consultation
                            </button>
                        @endif
                    </form>
                    @if ($priorite == null)
                        <form method="GET" action="{{ route('infirmier.show', $compte) }}">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach(App\Algorithms\Symptomes::cases() as $symptome)
                                    <label class="flex items-center gap-2">
                                        <x-checkbox
                                            id="{{ $symptome->value }}"
                                            name="symptomes[]"
                                            value="{{ $symptome->value }}"
                                        />

                                        <span>{{ mb_convert_case($symptome->value, MB_CASE_TITLE, 'UTF-8') }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-yellow-200 text-yellow-700 rounded">
                                Calculer la priorité
                            </button>
                        </form>
                    @endif
                @else
                    <h2 class="text-md font-medium mb-2">Vous n'êtes pas connecté.</h2>
                @endif
                <hr class="my-6 border-gray-300">
                <a href="{{ route('infirmier') }}" class="inline-block mt-4 px-4 py-2 bg-yellow-500 text-white rounded">Retour à la file d'attente</a>
            </div>
        </div>
    </div>
</x-app-layout>

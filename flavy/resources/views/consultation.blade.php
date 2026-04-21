<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Espace Médecin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-green-100 p-6 rounded shadow">
                <h3 class="text-lg font-medium mb-2">Espace Médecin</h3>
                @if (\App\Algorithms\TimeCard::loggedin(auth()->user()))
                    <form method="POST" action="{{ route('medecin.next', $compte) }}">
                        @csrf
                        @method('PATCH')
                        <hr class="my-6 border-gray-300">
                        <h2 class="text-md font-medium mb-2">{{ $compte->prenom }} {{ $compte->nom }}</h2>
                        <br>
                        <p><span class="font-semibold">Date de début de la visite :</span><br>
                            {{ $compte->visites()->latest('date_heure')->value('date_heure') }}
                        </p>
                        <br>
                        <p>
                            <span class="font-semibold">Commentaires de l'infirmière :</span><br>
                            {{ $compte->visites()->latest('date_heure')->first()?->diagnostique->commentaire }}
                        </p>
                        <br>
                        <x-label for="diagnostique" value="{{ __('Diagnostic') }}" class="mt-4" />
                        <x-input id="diagnostique" type="text" class="mt-1 block w-full" name="diagnostique" required />
                        <br>
                        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-green-500 text-white rounded">
                            Terminer la consultation de ce patient
                        </button>
                    </form>
                @else
                    <h2 class="text-md font-medium mb-2">Vous n'êtes pas connecté.</h2>
                @endif
                <hr class="my-6 border-gray-300">
                <a href="{{ url('/medecin') }}" class="inline-block mt-4 px-4 py-2 bg-green-500 text-white rounded">Retour à la file d'attente</a>
            </div>
        </div>
    </div>
</x-app-layout>

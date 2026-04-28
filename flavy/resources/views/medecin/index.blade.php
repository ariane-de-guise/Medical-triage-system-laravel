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
                @if (\App\Algorithms\TimeCard::loggedin(auth()->user(), \App\Algorithms\Roles::Medecin))
                    <form method="POST" action="{{ route('medecin.logout') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-green-500 text-white rounded">
                            Terminer la consultation de patients
                        </button>
                    </form>
                    <hr class="my-6 border-gray-300">
                    <h2 class="text-md font-medium mb-2">Patients dans la file</h2>
                    @if(\App\Algorithms\TriageQueue::queueCount(auth()->user()) === 0)
                        <p>Aucun patient dans la file</p>
                    @else
                        <div class="space-y-6">
                            @for($i = 0; $i < \App\Algorithms\TriageQueue::queueCount(auth()->user()); $i++)
                                @php $compte = \App\Algorithms\TriageQueue::peek($i, auth()->user()); @endphp
                                <div class="bg-white shadow-md rounded-xl p-5 border border-gray-200">

                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="font-semibold text-lg text-gray-800">
                                            {{ $compte->prenom }} {{ $compte->nom }}
                                        </h4>
                                        <span class="text-sm px-3 py-1 rounded-full bg-green-100 text-green-700">
                                        Priorité {{ $compte->triage->priority }}
                                    </span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">

                                        <div>
                                            <p><span class="font-semibold">Date de début de la visite :</span><br>
                                                {{ $compte->visites()->latest('date_heure')->value('date_heure') }}
                                            </p>
                                            @if ($i === 0)
                                                <form method="GET" action="{{ route('medecin.show', $compte) }}">
                                                    <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-green-100 text-green-700 rounded">
                                                        Prendre en charge
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        <div>
                                            <p>
                                                <span class="font-semibold">💊🩺 Commentaires de l'infirmière :</span><br>
                                                {{ $compte->visites()->latest('date_heure')->first()?->diagnostique->commentaire }}
                                            </p>

                                            <p class="mt-2 text-xs text-gray-500">
                                                Attends depuis {{ (int) (now()->diffInMinutes($compte->visites()->latest('date_heure')->value('date_heure'), true)) }} minutes
                                            </p>
                                        </div>

                                    </div>

                                </div>
                            @endfor
                        </div>
                    @endif
                @else
                    <form method="POST" action="{{ route('medecin.login') }}">
                        @csrf
                        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-green-500 text-white rounded">
                            Prêt à consulter
                        </button>
                    </form>
                @endif
                <hr class="my-6 border-gray-300">
                <a href="{{ route('dashboard') }}" class="inline-block mt-4 px-4 py-2 bg-green-500 text-white rounded">Retour au Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>

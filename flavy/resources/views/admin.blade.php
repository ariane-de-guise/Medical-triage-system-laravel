<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Espace Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-blue-100 p-6 rounded shadow">
                <h3 class="text-lg font-medium mb-2">Administration générale</h3>

                <p>
                    Seules les personnes avec le rôle <strong>admin</strong> peuvent voir cette page.
                </p>

                <a href="{{ route('register') }}"
                   class="inline-block mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Créer un utilisateur
                </a>

                <a href="{{ route('admin.roles') }}"
                   class="inline-block mt-4 ml-2 px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                    Gérer les rôles
                </a>

                <a href="{{ url('/dashboard') }}"
                   class="inline-block mt-4 ml-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Retour au Dashboard
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

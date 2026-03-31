<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Espace Patient') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-purple-100 p-6 rounded shadow">
                <h3 class="text-lg font-medium mb-2">Espace Patient</h3>
                <p>Seules les personnes avec le rôle <strong>patient</strong> peuvent voir cette page.</p>
                <a href="{{ url('/dashboard') }}" class="inline-block mt-4 px-4 py-2 bg-purple-500 text-white rounded">Retour au Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>

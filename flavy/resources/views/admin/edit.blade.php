<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestion des rôles
        </h2>
    </x-slot>

    <div class="p-6 max-w-xl">

        @if (session('status'))
            <div class="mb-4 text-green-600 font-medium">
                {{ session('status') }}
            </div>
        @endif

        <!-- AJOUT ROLE -->
        <form method="POST" action="{{ route('admin.roles.add') }}">
            @csrf

            <div>
                <x-label for="email" value="Email utilisateur" />
                <x-input id="email" type="email" name="email" class="block mt-1 w-full" required />
            </div>

            <div class="mt-4">
                <x-label for="role" value="Rôle à ajouter" />
                <select name="role" class="block mt-1 w-full border-gray-300 rounded-md">
                    <option value="patient">Patient</option>
                    <option value="infirmier">Infirmier</option>
                    <option value="medecin">Médecin</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <x-button class="mt-4">
                Ajouter rôle
            </x-button>
        </form>

        <hr class="my-6">

        <!-- RETIRER ROLE -->
        <form method="POST" action="{{ route('admin.roles.remove') }}">
            @csrf

            <div>
                <x-label for="email" value="Email utilisateur" />
                <x-input id="email" type="email" name="email" class="block mt-1 w-full" required />
            </div>

            <div class="mt-4">
                <x-label for="role" value="Rôle à retirer" />
                <select name="role" class="block mt-1 w-full border-gray-300 rounded-md">
                    <option value="patient">Patient</option>
                    <option value="infirmier">Infirmier</option>
                    <option value="medecin">Médecin</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <x-button class="mt-4 bg-red-600">
                Retirer rôle
            </x-button>
        </form>

    </div>
</x-app-layout>

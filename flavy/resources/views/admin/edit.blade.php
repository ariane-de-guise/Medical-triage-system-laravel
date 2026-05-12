<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="hospital-badge dark:text-gray-300">Rôles</p>
            <h2 class="mt-3 text-4xl font-black text-slate-950 dark:text-gray-100">
                Gestion des rôles
            </h2>
        </div>
    </x-slot>
    @if (session('status'))
        <div
            class="mb-6 hospital-card border-green-200 bg-green-50 dark:bg-green-900/20 p-4 font-bold text-green-800 dark:text-green-300">
            {{ session('status') }}
        </div>
    @endif
    <div class="grid gap-6 lg:grid-cols-2">
        <form method="POST" action="{{ route('admin.roles.add') }}" class="hospital-card p-7">
            @csrf
            <h3 class="text-2xl font-black dark:text-gray-100">
                Ajouter un rôle
            </h3>
            <div class="mt-6">
                <x-label for="email_add" value="Email utilisateur" class="hospital-label dark:text-gray-300"/>
                <x-input id="email_add" type="email" name="email" class="hospital-input" required/>
            </div>
            <div class="mt-4">
                <x-label for="role_add" value="Rôle à ajouter" class="hospital-label dark:text-gray-300"/>
                <select id="role_add" name="role" class="hospital-input">
                    <option value="patient">Patient</option>
                    <option value="infirmier">Infirmier</option>
                    <option value="medecin">Médecin</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button class="hospital-btn mt-6">
                Ajouter rôle
            </button>
        </form>
        <form method="POST" action="{{ route('admin.roles.remove') }}" class="hospital-card p-7">
            @csrf
            <h3 class="text-2xl font-black dark:text-gray-100">Retirer un rôle</h3>
            <div class="mt-6">
                <x-label for="email_remove" value="Email utilisateur" class="hospital-label dark:text-gray-300"/>
                <x-input id="email_remove" type="email" name="email" class="hospital-input" required/>
            </div>
            <div class="mt-4">
                <x-label for="role_remove" value="Rôle à retirer" class="hospital-label dark:text-gray-300"/>
                <select id="role_remove" name="role" class="hospital-input">
                    <option value="patient">Patient</option>
                    <option value="infirmier">Infirmier</option>
                    <option value="medecin">Médecin</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button
                class="mt-6 inline-flex rounded-2xl bg-red-600 px-5 py-3 text-sm font-black text-white shadow-lg hover:bg-red-700">
                Retirer rôle
            </button>
        </form>
    </div>
</x-app-layout>

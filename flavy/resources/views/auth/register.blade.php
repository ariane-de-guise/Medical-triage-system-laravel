<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div>
                <x-label for="email" value="Email" />
                <x-input id="email" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <x-label for="password" value="Password" />
                <x-input id="password" type="password" name="password" required />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="Confirm Password" />
                <x-input id="password_confirmation" type="password" name="password_confirmation" required />
            </div>

            <div class="mt-4">
                <x-label for="nom" value="Nom" />
                <x-input id="nom" type="text" name="nom" :value="old('nom')" required />
            </div>

            <div class="mt-4">
                <x-label for="prenom" value="Prénom" />
                <x-input id="prenom" type="text" name="prenom" :value="old('prenom')" required />
            </div>

            <div class="mt-4">
                <x-label for="date_naissance" value="Date de naissance" />
                <x-input id="date_naissance" type="date" name="date_naissance" :value="old('date_naissance')" required />
            </div>

            <div class="mt-4">
                <x-label for="rue" value="Rue" />
                <x-input id="rue" type="text" name="rue" :value="old('rue')" />
            </div>

            <div class="mt-4">
                <x-label for="numero" value="Numéro" />
                <x-input id="numero" type="number" name="numero" :value="old('numero')" />
            </div>

            <div class="mt-4">
                <x-label for="ville" value="Ville" />
                <x-input id="ville" type="text" name="ville" :value="old('ville')" />
            </div>

            <div class="mt-4">
                <x-label for="province" value="Province" />
                <x-input id="province" type="text" name="province" :value="old('province')" />
            </div>

            <div class="mt-4">
                <x-label for="code_postal" value="Code postal" />
                <x-input id="code_postal" type="text" name="code_postal" :value="old('code_postal')" />
            </div>

            <div class="mt-4">
                <x-label for="type" value="Type d’adresse" />
                <select id="type" name="type" class="block mt-1 w-full border-gray-300 rounded-md">
                    <option value="maison" {{ old('type') == 'maison' ? 'selected' : '' }}>Maison</option>
                    <option value="travail" {{ old('type') == 'travail' ? 'selected' : '' }}>Travail</option>
                </select>
            </div>

            <div class="mt-4">
                <x-label for="telephone" value="Téléphone" />
                <x-input id="telephone" type="text" name="telephone" :value="old('telephone')" />
            </div>

            @if(auth()->check() && auth()->user()->hasRole('admin'))
                <div class="mt-4">
                    <x-label for="role" value="Rôle" />
                    <select name="role" id="role" class="block mt-1 w-full border-gray-300 rounded-md">
                        <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                        <option value="infirmier" {{ old('role') == 'infirmier' ? 'selected' : '' }}>Infirmier</option>
                        <option value="medecin" {{ old('role') == 'medecin' ? 'selected' : '' }}>Médecin</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <x-button>Register</x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

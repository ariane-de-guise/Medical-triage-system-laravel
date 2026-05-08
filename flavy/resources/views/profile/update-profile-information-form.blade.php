<x-form-section submit="updateProfileInformation">
    <x-slot name="title">Informations du profil</x-slot>
    <x-slot name="description">Modifiez vos informations personnelles, vos coordonnées et votre photo de profil.</x-slot>

    <x-slot name="form">
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6">
                <input type="file" id="photo" class="hidden" wire:model.live="photo" x-ref="photo"
                       x-on:change="photoName = $refs.photo.files[0].name; const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL($refs.photo.files[0]);" />

                <x-label for="photo" value="Photo de profil" />
                <div class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-slate-50 p-5 sm:flex-row sm:items-center">
                    <div x-show="! photoPreview">
                        <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="h-24 w-24 rounded-3xl object-cover shadow-lg ring-4 ring-white">
                    </div>
                    <div x-show="photoPreview" style="display: none;">
                        <span class="block h-24 w-24 rounded-3xl bg-cover bg-center shadow-lg ring-4 ring-white" x-bind:style="'background-image: url(\'' + photoPreview + '\');'"></span>
                    </div>
                    <div class="flex-1">
                        <p class="font-black text-slate-900">Photo affichée dans le tableau de bord</p>
                        <p class="mt-1 text-sm text-slate-500">Format recommandé : image carrée, claire et professionnelle.</p>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <x-secondary-button type="button" x-on:click.prevent="$refs.photo.click()">Changer la photo</x-secondary-button>
                            @if ($this->user->profile_photo_path)
                                <x-secondary-button type="button" wire:click="deleteProfilePhoto">Retirer</x-secondary-button>
                            @endif
                        </div>
                    </div>
                </div>
                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <div class="col-span-6 sm:col-span-3"><x-label for="prenom" value="Prénom" /><x-input id="prenom" type="text" wire:model="state.prenom" /></div>
        <div class="col-span-6 sm:col-span-3"><x-label for="nom" value="Nom" /><x-input id="nom" type="text" wire:model="state.nom" /></div>
        <div class="col-span-6 sm:col-span-3"><x-label for="date_naissance" value="Date de naissance" /><x-input id="date_naissance" type="date" max="{{ now()->format('Y-m-d') }}" wire:model="state.date_naissance" /></div>
        <div class="col-span-6 sm:col-span-3"><x-label for="email" value="Email" /><x-input id="email" type="email" wire:model="state.email" required /></div>

        <div class="col-span-6"><div class="h-px bg-slate-100"></div></div>

        <div class="col-span-6 sm:col-span-2"><x-label for="numero" value="Numéro civique" /><x-input id="numero" type="text" wire:model="state.numero" /></div>
        <div class="col-span-6 sm:col-span-4"><x-label for="rue" value="Rue" /><x-input id="rue" type="text" wire:model="state.rue" /></div>
        <div class="col-span-6 sm:col-span-3"><x-label for="ville" value="Ville" /><x-input id="ville" type="text" wire:model="state.ville" /></div>
        <div class="col-span-6 sm:col-span-3"><x-label for="province" value="Province" /><x-input id="province" type="text" wire:model="state.province" /></div>
        <div class="col-span-6 sm:col-span-3"><x-label for="code_postal" value="Code postal" /><x-input id="code_postal" type="text" wire:model="state.code_postal" /></div>
        <div class="col-span-6 sm:col-span-3">
            <x-label for="type" value="Type d’adresse" />
            <select id="type" class="hospital-input" wire:model="state.type">
                <option value="home">Maison</option>
                <option value="work">Travail</option>
            </select>
        </div>
        <div class="col-span-6 sm:col-span-3"><x-label for="telephone" value="Téléphone" /><x-input id="telephone" type="text" wire:model="state.telephone" /></div>

        <div class="col-span-6">
            <x-input-error for="prenom" class="mt-2" /><x-input-error for="nom" class="mt-2" /><x-input-error for="date_naissance" class="mt-2" /><x-input-error for="email" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3 text-sm font-bold text-emerald-700" on="saved">Enregistré.</x-action-message>
        <x-button wire:loading.attr="disabled" wire:target="photo">Sauvegarder</x-button>
    </x-slot>
</x-form-section>

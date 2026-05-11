<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Livewire\Component;
use Livewire\WithFileUploads;

class CustomUpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    /**
     * The new avatar for the user.
     *
     * @var mixed
     */
    public $photo;

    /**
     * Determine if the verification email was sent.
     *
     * @var bool
     */
    public $verificationLinkSent = false;

    /**
     * Prepare the component.
     *
     * @return void
     */
    public function mount(): void
    {
        $user = Auth::user();

        $this->state = array_merge([
            'email' => $user->email,
        ], $user->withoutRelations()->toArray());

        $adresse = $user->compte->adresses()->first();
        $telephone = $user->compte->telephones()->first();

        $this->state['nom'] = $user->compte->nom ?? '';
        $this->state['prenom'] = $user->compte->prenom ?? '';
        $this->state['date_naissance'] = $user->compte->date_naissance ?? '';

        $this->state['numero'] = $adresse->numero ?? '';
        $this->state['rue'] = $adresse->rue ?? '';
        $this->state['ville'] = $adresse->ville ?? '';
        $this->state['province'] = $adresse->province ?? '';
        $this->state['code_postal'] = $adresse->code_postal ?? '';
        if (!$adresse || $adresse->type == 'maison') {
            $this->state['type'] = 'home';
        } else {
            $this->state['type'] = 'work';
        }
        $this->state['telephone'] = $telephone->numero ?? '';
    }

    /**
     * Update the user's profile information.
     *
     * @param UpdatesUserProfileInformation $updater
     * @return RedirectResponse|null
     */
    public function updateProfileInformation(UpdatesUserProfileInformation $updater): ?RedirectResponse
    {
        $this->resetErrorBag();

        $updater->update(
            Auth::user(),
            $this->photo
                ? array_merge($this->state, ['photo' => $this->photo])
                : $this->state
        );

        if (isset($this->photo)) {
            return redirect()->route('profile.show');
        }

        $this->dispatch('saved');

        $this->dispatch('refresh-navigation-menu');
        return null;
    }

    /**
     * Delete user's profile photo.
     *
     * @return void
     */
    public function deleteProfilePhoto(): void
    {
        Auth::user()->deleteProfilePhoto();

        $this->dispatch('refresh-navigation-menu');
    }

    /**
     * Sent the email verification.
     *
     * @return void
     */
    public function sendEmailVerification(): void
    {
        Auth::user()->sendEmailVerificationNotification();

        $this->verificationLinkSent = true;
    }

    /**
     * Get the current user of the application.
     *
     * @return User|Authenticatable|null
     */
    public function getUserProperty(): User|Authenticatable|null
    {
        return Auth::user();
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('profile.update-profile-information-form');
    }
}

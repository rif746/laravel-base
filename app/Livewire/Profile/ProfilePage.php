<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class ProfilePage extends Component
{
    protected $listeners = ['profile:update' => '$refresh'];

    public function render()
    {
        return view('livewire.profile.profile-page');
    }

    public function verifyEmail()
    {
        auth()->user()->sendEmailVerificationNotification();
        $this->js("emailNotif = true");
        $this->js("setTimeout(() => {emailNotif = false}, 3500)");
    }

    public function updatePreference($key, $value)
    {
        setPreference($key, $value);
        if ($key == 'theme') {
            $this->dispatch('change-theme', dark: $value == 'dark');
        }
    }

    public function isPreference($key, $value)
    {
        return preferenceIs($key, $value) ? 'true' : 'false';
    }
}

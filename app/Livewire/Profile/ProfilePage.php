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
}

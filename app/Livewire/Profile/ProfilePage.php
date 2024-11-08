<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Mary\Traits\Toast;

class ProfilePage extends Component
{
    use Toast;
    use WithFileUploads;

    public $theme;

    public $lang;

    public $avatar;

    protected $listeners = ['profile:update' => '$refresh'];

    public function mount()
    {
        $preference = auth('web')->user()->settings;
        $this->theme = $preference['theme'] ?? null;
        $this->lang = $preference['lang'] ?? null;
    }

    public function render()
    {
        return view('livewire.profile.profile-page');
    }

    public function verifyEmail()
    {
        auth('web')->user()->sendEmailVerificationNotification();
        $this->success('Email verification has sent.');
    }

    public function saveSettings()
    {

        $settings = [
            'theme' => $this->theme,
            'lang' => $this->lang,
        ];
        auth('web')->user()->update(['settings' => $settings]);
        $this->dispatch('theme-changed', theme: $this->theme);
        $this->success('Setting Updated!', 'Refreshing this page to take effect.');
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function saveAvatar()
    {
        $this->validate([
            'avatar' => 'dimensions:ratio=1/1',
        ]);
        $user = auth('web')->user();
        if ($user->photo_profile) {
            deleteFile($user->photo_profile);
        }
        $avatar = $this->avatar?->store('avatar');
        auth('web')->user()->update(['photo_profile' => $avatar]);
        $this->avatar = null;
        $this->resetValidation();
    }
}

<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Mary\Traits\Toast;

class ProfilePage extends Component
{
    use Toast;

    public $theme;

    public $lang;

    protected $listeners = ['profile:update' => '$refresh'];

    public function mount()
    {
        $preference = auth('web')->user()->settings;
        $this->theme = $preference['theme'] ?? null;
        $this->lang = $preference['lang'] ?? null;
    }

    public function updated($props, $val)
    {
        $settings = [
            'theme' => $this->theme,
            'lang' => $this->lang
        ];
        auth('web')->user()->update(['settings' => $settings]);

        if($props == 'theme') {
            $this->dispatch('theme-changed', theme: $val);
        }
    }

    public function render()
    {
        return view('livewire.profile.profile-page');
    }

    public function verifyEmail()
    {
        auth('web')->user()->sendEmailVerificationNotification();
        $this->success("Email verification has sent.");
    }
}

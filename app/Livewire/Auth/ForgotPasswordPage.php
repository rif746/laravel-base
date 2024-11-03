<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Mary\Traits\Toast;

class ForgotPasswordPage extends Component
{
    use Toast;

    #[Rule(['required', 'email'])]
    public $email;

    #[Layout('components.layouts.auth')]
    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }

    public function send_reset()
    {
        $this->validate();

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            $this->success(__($status));
        } else {
            $this->addError('email', __($status));
        }
    }
}

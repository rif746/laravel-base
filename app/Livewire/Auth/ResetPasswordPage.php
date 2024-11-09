<?php

namespace App\Livewire\Auth;

use App\Livewire\Attributes\Metadata;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Mary\Traits\Toast;

#[Metadata('Reset Password')]
class ResetPasswordPage extends Component
{
    use Toast;

    #[Url('email')]
    #[Rule(['required', 'string', 'email'])]
    public $email;

    #[Rule(['required', 'string', 'confirmed'])]
    public $password;
    public $password_confirmation;

    public $token;

    public function mount($token)
    {
        $this->token = $token;
    }

    public function rules()
    {
        return [
            'password' => [Rules\Password::default()]
        ];
    }

    #[Layout("components.layouts.auth")]
    public function render()
    {
        return view('livewire.auth.reset-password-page');
    }

    public function store()
    {
        $this->validate();

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => bcrypt($this->password),
                    'remember_token' => Str::random(),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status == Password::PASSWORD_RESET) {
            return $this->success('Password changed!', 'You can login with new password now', redirectTo: '/login');
        } else {
            $this->addError('email', __($status));
        }
    }
}

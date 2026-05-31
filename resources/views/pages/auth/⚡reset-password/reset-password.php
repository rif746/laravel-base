<?php

use App\Attributes\Seo;
use App\Concerns\Livewire\Seo\HasSeoAttributes;
use App\Models\Identity\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Layout('components.layouts.guest', ['title' => 'domains/auth.pages.reset_password.header'])]
#[Seo(title: 'domains/auth.seo.reset_password.title', description: 'domains/auth.seo.reset_password.description', keywords: 'domains/auth.seo.reset_password.keywords')]
class extends Component
{
    use HasSeoAttributes;

    public string $token;

    #[Url('email')]
    public string $email;

    public string $password;

    public string $password_confirmation;

    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->all(),
            function (User $user) {
                $user->forceFill([
                    'password' => $this->password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            session()->flash('status', __('domains/auth.messages.password_reset'));
            $this->redirectRoute('login');
        } else {
            $this->addError('email', __('domains/auth.messages.invalid_token'));
        }
    }
};

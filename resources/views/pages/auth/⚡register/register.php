<?php

use App\Attributes\Seo;
use App\Concerns\Livewire\Seo\HasSeoAttributes;
use App\Domains\Identity\Actions\Registration\RegisterUser;
use App\Domains\Identity\DTOs\Registration\RegisterUserDTO;
use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.guest', ['title' => 'domains/auth.pages.register.header'])]
#[Seo(title: 'domains/auth.seo.register.title', description: 'domains/auth.seo.register.description', keywords: 'domains/auth.seo.register.keywords')]
class extends Component
{
    use HasSeoAttributes;

    public string $email;

    public string $name;

    public string $password;

    public string $password_confirmation;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique(User::class, 'name')],
            'email' => ['required', 'string', 'email', Rule::unique(User::class, 'email')],
            'password' => [Password::default(), 'required', 'confirmed'],
        ];
    }

    public function register(RegisterUser $action): void
    {
        $this->validate();

        // The Identity Domain creates the user and fires the Registered event.
        $user = $action->execute(new RegisterUserDTO(
            name: $this->name,
            email: $this->email,
            password: $this->password,
        ));

        // The Gateway owns the session — Auth::login() lives here, not in the domain.
        Auth::login($user);

        $this->redirectRoute('dashboard', absolute: false, navigate: true);
    }
};

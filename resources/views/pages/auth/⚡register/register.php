<?php

use App\Attributes\Seo;
use App\Concerns\Livewire\Seo\HasSeoAttributes;
use App\Models\Identity\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule as ValidationRule;
use Illuminate\Validation\Rules\Password as RulesPassword;
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
            'name' => ['required', 'string', 'max:255', ValidationRule::unique(User::class, 'name')],
            'email' => ['required', 'string', 'email', ValidationRule::unique(User::class, 'email')],
            'password' => [RulesPassword::default(), 'required', 'confirmed'],
        ];
    }

    public function register(): void
    {
        $this->validate();
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        $this->redirectRoute('dashboard', absolute: false, navigate: true);
    }
};

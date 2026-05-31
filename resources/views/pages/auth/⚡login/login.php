<?php

use App\Actions\Auth\LoginUser;
use App\Attributes\Seo;
use App\Concerns\Livewire\Seo\HasSeoAttributes;
use App\DTOs\Auth\LoginUserDTO;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.guest', ['title' => 'domains/auth.pages.login.header'])]
#[Seo(title: 'domains/auth.seo.login.title', description: 'domains/auth.seo.login.description', keywords: 'domains/auth.seo.login.keywords')]
class extends Component
{
    use HasSeoAttributes;

    public string $email;

    public string $password;

    public bool $remember = false;

    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ];
    }

    public function login(LoginUser $action): void
    {
        $this->validate();

        $action->execute(new LoginUserDTO(
            email: $this->email,
            password: $this->password,
            remember: $this->remember,
        ));

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
};

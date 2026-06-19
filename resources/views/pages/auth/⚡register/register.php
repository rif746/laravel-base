<?php

use App\Attributes\Seo;
use App\Domains\Identity\Actions\Onboarding\RegisterSelfServiceUser;
use App\Domains\Identity\DTOs\Onboarding\RegisterSelfServiceUserDTO;
use App\Livewire\Concerns\HasSeoAttributes;
use App\Livewire\Forms\Auth\RegisterForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.guest', ['title' => 'domains/auth/pages.register.header'])]
#[Seo(title: 'domains/auth/seo.register.title', description: 'domains/auth/seo.register.description', keywords: 'domains/auth/seo.register.keywords')]
class extends Component
{
    use HasSeoAttributes;

    public RegisterForm $form;

    public function register(RegisterSelfServiceUser $action): void
    {
        $this->form->validate();

        // The Identity Domain creates the user and fires the Registered event.
        $user = $action->execute(new RegisterSelfServiceUserDTO(
            name: $this->form->name,
            email: $this->form->email,
            password: $this->form->password,
        ));

        // The Gateway owns the session — Auth::login() lives here, not in the domain.
        Auth::login($user);

        $this->redirectRoute('dashboard', absolute: false, navigate: true);
    }
};

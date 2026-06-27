<?php

use App\Attributes\LayoutData;
use App\Attributes\Seo;
use App\Domains\Identity\Actions\Governance\ActivateUserStatus;
use App\Domains\Identity\Actions\Governance\SuspendUser;
use App\Domains\Identity\Actions\Passwords\SendPasswordResetLink;
use App\Domains\Identity\DTOs\Passwords\ForgotPasswordDTO;
use App\Domains\Identity\Models\User;
use App\Livewire\Concerns\HasLayoutDataAttributes;
use App\Livewire\Concerns\HasSeoAttributes;
use App\Livewire\Concerns\WithToast;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

new #[Layout('components.layouts.app')]
#[LayoutData(header: 'domains/identity/seo.user_detail.title', breadcrumbs: ['ui.menu.dashboard' => 'dashboard', 'domains/identity/seo.user.title' => 'users.index', '{name}' => ''], context: 'user')]
#[Seo(title: 'domains/identity/seo.user_detail.title', context: 'user')]
class extends Component
{
    use HasLayoutDataAttributes;
    use HasSeoAttributes;
    use WithToast;

    #[Locked]
    public ?string $id = null;

    public function mount(string $user_id): void
    {
        $this->id = $user_id;
    }

    #[On('refresh-user-data')]
    #[Computed]
    public function user(): ?User
    {
        return $this->id ? User::with('profile')
            ->where('ulid', $this->id)
            ->first() : new User;
    }

    #[On('send-password-reset')]
    public function sendResetPassword(SendPasswordResetLink $sendLink): void
    {
        try {
            $sendLink->execute(new ForgotPasswordDTO(
                email: $this->user->email,
            ));

            $this->dispatch('send-password-reset-completed');
        } catch (Exception $e) {
            $this->dispatch('send-password-reset-failed', message: $e->getMessage());
        }
    }

    #[On('toggle-user-status')]
    public function toggleStatus(SuspendUser $suspendUser, ActivateUserStatus $activateUserStatus): void
    {
        try {
            if ($this->user->status->isActive()) {
                $suspendUser->execute($this->user);
            } else {
                $activateUserStatus->execute($this->user);
            }

            $this->dispatch('toggle-user-status-completed');
        } catch (Exception $e) {
            $this->dispatch('toggle-user-status-failed', message: $e->getMessage());
        }
    }
};

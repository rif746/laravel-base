<?php

use App\Domains\Identity\Queries\Dashboard\GetTotalUsers;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function userCount()
    {
        return GetTotalUsers::fetch();
    }
};
?>

<div class="card bg-primary bg-opacity-10 border-primary border-opacity-25 rounded-2 border p-4">
    <div class="d-flex gap-3">
        <div class="icon-shape icon-md bg-primary rounded-2 text-white">
            @svg('tabler-users-group', [
                'class' => 'fs-4',
            ])
        </div>
        <div>
            <h2 class="fs-6 mb-3" data-heading-tag="H2">{{ __('domains/identity/dashboard.user_count.title') }}</h2>
            <h3 class="fw-bold mb-0" data-heading-tag="H3">{{ $this->userCount['total_users'] }}</h3>
            <p class="text-primary small mb-0">
                {{ __('domains/identity/dashboard.user_count.growth', ['rate' => $this->userCount['growth_rate']]) }}
            </p>
        </div>
    </div>
</div>

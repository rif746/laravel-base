<?php

use App\Domains\Identity\Queries\Dashboard\GetMonthlyNewUsers;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function userCount()
    {
        return GetMonthlyNewUsers::fetch();
    }
};
?>

<div class="card bg-info bg-opacity-10 border-info border-opacity-25 rounded-2 border p-4">
    <div class="d-flex gap-3">
        <div class="icon-shape icon-md bg-info rounded-2 text-white">
            @svg('tabler-user-star', [
                'class' => 'fs-4',
            ])
        </div>
        <div>
            <h2 class="fs-6 mb-3" data-heading-tag="H2">{{ __('domains/identity/dashboard.new_user_count.title') }}</h2>
            <h3 class="fw-bold mb-0" data-heading-tag="H3">{{ $this->userCount['new_users'] }}</h3>
            <p class="text-info small mb-0">
                {{ __('domains/identity/dashboard.new_user_count.growth', ['rate' => $this->userCount['growth_rate']]) }}
            </p>
        </div>
    </div>
</div>

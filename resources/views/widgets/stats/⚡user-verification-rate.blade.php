<?php

use App\Domains\Identity\Queries\Dashboard\GetUserVerificationRates;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function userCount()
    {
        return GetUserVerificationRates::fetch();
    }
};
?>

<div class="card bg-warning bg-opacity-10 border-warning border-opacity-25 rounded-2 border p-4">
    <div class="d-flex gap-3">
        <div class="icon-shape icon-md bg-warning rounded-2 text-white">
            @svg('tabler-user-check', [
                'class' => 'fs-4',
            ])
        </div>
        <div>
            <h2 class="fs-6 mb-3" data-heading-tag="H2">
                {{ __('domains/identity/dashboard.user_verification_rate.title') }}
            </h2>
            <h3 class="fw-bold mb-0" data-heading-tag="H3">{{ $this->userCount['verification_rate'] }}%</h3>
            <p class="text-warning small mb-0">
                {{ __('domains/identity/dashboard.user_verification_rate.detail', ['verified' => $this->userCount['verified'], 'unverified' => $this->userCount['unverified']]) }}
            </p>
        </div>
    </div>
</div>

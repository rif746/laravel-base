<?php

use App\Domains\Identity\Queries\Dashboard\GetUserVerificationRates;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    #[Computed]
    public function userCount()
    {
        return app(GetUserVerificationRates::class)->fetch();
    }
};
?>

<div class="card p-4 bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-2">
    <div class="d-flex gap-3 ">
        <div class="icon-shape icon-md bg-warning text-white rounded-2">
            @svg('tabler-user-check', [
                'class' => 'fs-4'
            ])
        </div>
        <div>
            <h2 class="mb-3 fs-6" data-heading-tag="H2">{{ __('domains/identity/dashboard.user_verification_rate.title') }}</h2>
            <h3 class="fw-bold mb-0" data-heading-tag="H3">{{ $this->userCount['verification_rate'] }}%</h3>
            <p class="text-warning mb-0 small">{{ __('domains/identity/dashboard.user_verification_rate.detail', ['verified' => $this->userCount['verified'], 'unverified' => $this->userCount['unverified']]) }}</p>
        </div>
    </div>
</div>

<?php

use App\Domains\Identity\Queries\Dashboard\GetTotalUsers;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    #[Computed]
    public function userCount()
    {
        return app(GetTotalUsers::class)->fetch();
    }
};
?>

<div class="card p-4  bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-2">
    <div class="d-flex gap-3 ">
        <div class="icon-shape icon-md bg-primary text-white rounded-2">
            @svg('tabler-users-group', [
                'class' => 'fs-4'
            ])
        </div>
        <div>
            <h2 class="mb-3 fs-6" data-heading-tag="H2">{{ __('domains/identity/dashboard.user_count.title') }}</h2>
            <h3 class="fw-bold mb-0" data-heading-tag="H3">{{ $this->userCount['total_users'] }}</h3>
            <p class="text-primary mb-0 small">{{ __('domains/identity/dashboard.user_count.growth', ['rate' => $this->userCount['growth_rate']]) }}</p>
        </div>
    </div>
</div>

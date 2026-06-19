<?php

use App\Domains\Identity\Queries\Dashboard\GetMonthlyNewUsers;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    #[Computed]
    public function userCount()
    {
        return app(GetMonthlyNewUsers::class)->fetch();
    }
};
?>

<div class="card p-4 bg-info bg-opacity-10 border border-info border-opacity-25 rounded-2">
    <div class="d-flex gap-3 ">
        <div class="icon-shape icon-md bg-info text-white rounded-2">
            @svg('tabler-user-star', [
                'class' => 'fs-4'
            ])
        </div>
        <div>
            <h2 class="mb-3 fs-6" data-heading-tag="H2">Monthly New Users</h2>
            <h3 class="fw-bold mb-0" data-heading-tag="H3">{{ $this->userCount['new_users'] }}</h3>
            <p class="text-info mb-0 small">{{ $this->userCount['growth_rate'] }} since last month</p>
        </div>
    </div>
</div>

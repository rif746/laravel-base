<?php

use App\Domains\Identity\Queries\Dashboard\GetUserGrowthTrends;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function userGrowth(): array
    {
        return GetUserGrowthTrends::fetch();
    }

    #[Computed]
    public function chartOptions(): array
    {
        return [
            'chart' => [
                'type' => 'line',
                'height' => 360,
                'toolbar' => ['show' => false],
            ],
            'series' => [[
                'name' => __('domains/identity/dashboard.user_growth.series_name'),
                'data' => $this->userGrowth['series'],
            ]],
            'xaxis' => [
                'categories' => $this->userGrowth['categories'],
            ],
            'colors' => ['#4f46e5'],
        ];
    }
};
?>

<div class="card bg-opacity-10 border-opacity-25 rounded-2 border">
    <div class="card-body">
        <div class="d-flex gap-3">
            <div class="icon-shape icon-md bg-secondary rounded-2 text-white">
                @svg('tabler-user-pin', ['class' => 'fs-4'])
            </div>
            <div>
                <h2 class="fs-4">{{ __('domains/identity/dashboard.user_growth.title') }}</h2>
                <p class="text-secondary small mb-0">
                    {{ __('domains/identity/dashboard.user_growth.subtitle', ['year' => now()->year]) }}
                </p>
            </div>
        </div>

        {{-- Direct JSON binding ensures initial render --}}
        <div id="user-growth" x-chart='@json($this->chartOptions)'></div>
    </div>
</div>

<?php

use App\Domains\Identity\Queries\Dashboard\GetUserGrowthTrends;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {

    public function mount(): void
    {
        $this->dispatch('chart-user-growth-update',
            series: [[
                'name' => __('domains/identity/dashboard.user_growth.series_name'),
                'data' => $this->userGrowth['series']
            ]],
            options: [
                'xaxis' => [
                    'categories' => $this->userGrowth['categories']
                ]
            ]
        );
    }

    #[Computed]
    public function userGrowth(): array
    {
        return app(GetUserGrowthTrends::class)->fetch();
    }
};
?>
<div class="card bg-opacity-10 border border border-opacity-25 rounded-2">
    <div class="card-body">
        <div class="d-flex gap-3 ">
            <div class="icon-shape icon-md bg-secondary text-white rounded-2">
                @svg('tabler-user-pin', [
                    'class' => 'fs-4'
                ])
            </div>
            <div>
                <h2 class="fs-4">{{ __('domains/identity/dashboard.user_growth.title') }}</h2>
                <p class="text-secondary mb-0 small">{{ __('domains/identity/dashboard.user_growth.subtitle', ['year' => now()->year]) }}</p>
            </div>
        </div>
        <div id="user-growth" x-chart="{
            chart: {
                type: 'line',
                height: 360,
                toolbar: {show: false}
            },
            series: [],
            colors: ['#4f46e5']
        }"></div>
    </div>
</div>

@once
    @push('page-scripts')
        @vite('resources/js/plugin/apexchart.js')
    @endpush
@endonce

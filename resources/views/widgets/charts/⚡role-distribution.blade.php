<?php

use App\Domains\Identity\Queries\Dashboard\GetRoleDistributions;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function roleDistributions(): array
    {
        return GetRoleDistributions::fetch();
    }

    #[Computed]
    public function chartOptions(): array
    {
        return [
            'chart' => [
                'type' => 'pie',
                'width' => '100%',
                'height' => 360,
            ],
            'series' => $this->roleDistributions['series'],
            'labels' => $this->roleDistributions['categories'],
            'plotOptions' => [
                'pie' => [
                    'dataLabels' => [
                        'offset' => 1,
                    ],
                ],
            ],
            'theme' => [
                'monochrome' => [
                    'enabled' => true,
                ],
            ],
            'dataLabels' => [
                'formatter' => 'JS_RAW_FORMATTER', // Replaced in Blade wrapper
            ],
            'legend' => [
                'show' => false,
            ],
        ];
    }
};
?>

<div class="card bg-opacity-10 border-opacity-25 rounded-2 border">
    <div class="card-body">
        <div class="d-flex gap-3 mb-3">
            <div class="icon-shape icon-md bg-secondary rounded-2 text-white">
                @svg('tabler-user-shield', [
                    'class' => 'fs-4',
                ])
            </div>
            <div>
                <h2 class="fs-4">{{ __('domains/identity/dashboard.role_distribution.title') }}</h2>
                <p class="text-secondary small mb-0">
                    {{ __('domains/identity/dashboard.role_distribution.subtitle') }}
                </p>
            </div>
        </div>

        {{-- Direct JSON data binding with JS closure support for dataLabels --}}
        <div
            id="role-distribution"
            x-chart="{
                ...{{ json_encode($this->chartOptions) }},
                dataLabels: {
                    formatter(val, opts) {
                        const name = opts.w.globals.labels[opts.seriesIndex];
                        return [name, val.toFixed(1) + '%'];
                    }
                }
            }"
        ></div>
    </div>
</div>

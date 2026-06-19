<?php

use App\Domains\Identity\Queries\Dashboard\GetRoleDistributions;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {

    public function mount(): void
    {
        $this->dispatch('chart-role-distribution-update',
            series: $this->roleDistributions['series'],
            options: [
                'labels' =>  $this->roleDistributions['categories']
            ]
        );
    }

    #[Computed]
    public function roleDistributions(): array
    {
        return app(GetRoleDistributions::class)->fetch();
    }
};
?>
<div class="card bg-opacity-10 border border border-opacity-25 rounded-2">
    <div class="card-body">
        <div class="d-flex gap-3 ">
            <div class="icon-shape icon-md bg-secondary text-white rounded-2">
                @svg('tabler-user-shield', [
                    'class' => 'fs-4'
                ])
            </div>
            <div>
                <h2 class="fs-4">Role Distributions</h2>
                <p class="text-secondary mb-0 small">User role distributions</p>
            </div>
        </div>
        <div id="role-distribution" x-chart="{
            series: [],
            chart: {
                width: 450,
                type: 'pie',
            },
            labels: [],
            plotOptions: {
                pie: {
                    dataLabels: {
                        offset: 1,
                    },
                },
            },
            theme: {
                monochrome: {
                    enabled: true,
                },
            },
            dataLabels: {
                formatter(val, opts) {
                    const name = opts.w.globals.labels[opts.seriesIndex];
                    return [name, val.toFixed(1)];
                },
            },
            legend: {
                show: false
            }
        }"></div>
    </div>
</div>

@once
    @push('page-scripts')
        @vite('resources/js/plugin/apexchart.js')
    @endpush
@endonce

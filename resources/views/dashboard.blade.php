<x-layouts.app>
    <div class="container-fluid">
        @can('dashboard.admin')
            <div class="row g-3 mb-3">
                <div class="col-lg-4 col-12">
                    <livewire:widgets::stats.user-count />
                </div>
                <div class="col-lg-4 col-12">
                    <livewire:widgets::stats.new-user-count />
                </div>
                <div class="col-lg-4 col-12">
                    <livewire:widgets::stats.user-verification-rate />
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-lg-8 col-12">
                    <livewire:widgets::charts.user-growth />
                </div>
                <div class="col-lg-4 col-12">
                    <livewire:widgets::charts.role-distribution />
                </div>
            </div>
        @endcan
    </div>
</x-layouts.app>

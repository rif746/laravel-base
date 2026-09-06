<x-layouts.guest>
    <div class="vh-100 d-flex flex-column align-items-center justify-content-center p-3 overflow-hidden">
        <div class="card border border-2 border-body-tertiary shadow-sm rounded-4 w-100 overflow-hidden" style="max-width: 420px;">
            <div class="card-body p-4 p-sm-5 text-center d-flex flex-column gap-3 justify-content-center align-items-center">

                <!-- Header Brand & Security Badge (Logo | Shield) -->
                <div class="d-inline-flex align-items-center justify-content-center gap-3 bg-body-tertiary border border-body-tertiary px-3 py-2 rounded-pill">
                    <a href="{{ url('/') }}" class="d-inline-flex align-items-center transition-all hover-opacity-80">
                        <img src="@logoPath" alt="{{ config('app.name') }}" width="32" height="32" class="img-fluid" />
                    </a>

                    <div class="vr opacity-25" style="height: 35px;"></div>

                    <div class="text-danger d-inline-flex align-items-center justify-content-center">
                        <x-tabler-shield-lock width="26" height="26" stroke-width="1.75" />
                    </div>
                </div>

                <div>
                    <!-- Status Code & Titles -->
                    <h1 class="fw-bold text-body fs-2 mb-1">403</h1>
                    <p class="fw-semibold text-body fs-6 mb-1">{{ __('ui/errors.oops') }}</p>
                    <p class="text-body-secondary small mb-4 px-2">{{ __('ui/errors.403') }}</p>

                    <!-- Action Button -->
                    <x-link
                        href="{{ route('dashboard') }}"
                        class="btn btn-primary w-100 py-2 fw-medium d-inline-flex align-items-center justify-content-center gap-2"
                        :label="__('ui/errors.take_me_home')"
                        icon="tabler-arrow-left"
                        :icon-config="['width' => 18, 'height' => 18]"
                    />
                </div>

            </div>
        </div>
    </div>
</x-layouts.guest>

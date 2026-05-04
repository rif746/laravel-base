<x-layout.guest>
    <div class="card" style="max-width:420px; width:100%;">
        <div class="card-body p-5">

            {{-- Logo --}}
            <div class="mb-4 text-center">
                <a href="/" class="d-inline-block mb-4">
                    <img src="/assets/images/logo-icon.svg" alt="" width="36">
                    <span class="ms-2"><img src="/assets/images/logo.svg" alt=""></span>
                </a>
                <h1 class="card-title h5 mb-1">{{ __('domains/auth.pages.oauth.device.connect_header') }}</h1>
                <p class="small text-muted mb-0">{{ __('domains/auth.pages.oauth.device.connect_subheader') }}</p>
            </div>

            <hr class="my-3">

            {{-- Error message --}}
            @if ($errors->has('user_code'))
                <div class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3 mb-3" role="alert">
                    <i class="ti ti-alert-circle flex-shrink-0"></i>
                    <span class="small">{{ $errors->first('user_code') }}</span>
                </div>
            @endif

            <form method="GET" action="{{ route('passport.device.authorizations.authorize') }}" class="needs-validation" novalidate>
                <div class="mb-4">
                    <label for="user_code" class="form-label fw-semibold">{{ __('domains/auth.fields.oauth.device.user_code') }}</label>
                    <input
                        type="text"
                        id="user_code"
                        name="user_code"
                        class="form-control form-control-lg text-center text-uppercase letter-spacing-2 @error('user_code') is-invalid @enderror"
                        placeholder="XXXX-XXXX"
                        value="{{ old('user_code') }}"
                        autocomplete="off"
                        autofocus
                        required
                    >
                    <div class="form-text text-center mt-2">
                        <i class="ti ti-info-circle me-1"></i>
                        {{ __('domains/auth.pages.oauth.device.user_code_hint') }}
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="ti ti-arrow-right me-1"></i> {{ __('domains/auth.fields.oauth.device.continue') }}
                </button>
            </form>

        </div>
    </div>
</x-layout.guest>

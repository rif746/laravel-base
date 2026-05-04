<x-layout.guest>
    <div class="card" style="max-width:460px; width:100%;">
        <div class="card-body p-5">

            {{-- Logo --}}
            <div class="mb-4 text-center">
                <a href="/" class="d-inline-block mb-4">
                    <img src="/assets/images/logo-icon.svg" alt="" width="36">
                    <span class="ms-2"><img src="/assets/images/logo.svg" alt=""></span>
                </a>
                <h1 class="card-title h5 mb-1">{{ __('domains/auth.pages.oauth.device.header') }}</h1>
                <p class="small text-muted mb-0">{{ __('domains/auth.pages.oauth.device.subheader') }}</p>
            </div>

            <hr class="my-3">

            {{-- Client info --}}
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="flex-shrink-0 bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center"
                    style="width:44px; height:44px;">
                    <i class="ti ti-device-desktop fs-5 text-primary"></i>
                </div>
                <div>
                    <p class="mb-0 fw-semibold">{{ $client->name }}</p>
                    <p class="mb-0 small text-muted">{{ __('domains/auth.pages.oauth.authorize.is_requesting') }}</p>
                </div>
            </div>

            {{-- Authenticated user --}}
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="flex-shrink-0 bg-secondary-subtle rounded-circle d-flex align-items-center justify-content-center"
                    style="width:44px; height:44px;">
                    <i class="ti ti-user fs-5 text-secondary"></i>
                </div>
                <div>
                    <p class="mb-0 fw-semibold">{{ $user->name }}</p>
                    <p class="mb-0 small text-muted">{{ $user->email }}</p>
                </div>
            </div>

            {{-- Scopes --}}
            @if (count($scopes) > 0)
                <div class="mb-4">
                    <p class="small fw-semibold text-uppercase text-muted mb-2">{{ __('domains/auth.pages.oauth.authorize.requested_permissions') }}</p>
                    <ul class="list-unstyled mb-0">
                        @foreach ($scopes as $scope)
                            <li class="d-flex align-items-center gap-2 mb-1 small">
                                <i class="ti ti-circle-check text-success"></i>
                                {{ $scope->description }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Approve --}}
            <form method="POST" action="{{ route('passport.device.authorizations.approve') }}" class="mb-2">
                @csrf
                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ti ti-check me-1"></i> {{ __('domains/auth.fields.oauth.device.submit') }}
                </button>
            </form>

            {{-- Deny --}}
            <form method="POST" action="{{ route('passport.device.authorizations.deny') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                <button type="submit" class="btn btn-outline-secondary w-100">
                    <i class="ti ti-x me-1"></i> {{ __('domains/auth.fields.oauth.authorize.cancel') }}
                </button>
            </form>

        </div>
    </div>
</x-layout.guest>

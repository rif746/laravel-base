<x-layout.guest>
    <div class="card" style="max-width:420px; width:100%;">
        <div class="card-body p-5">
            <div class="mb-3 text-center">
                <a href="/" class="d-inline-block mb-4"><img src="./assets/images/logo-icon.svg" alt=""
                        width="36">
                    <span class="ms-2"> <img src="./assets/images/logo.svg" alt=""></span>
                </a>
                <h1 class="card-title h5 mb-5">{{ __('domains/auth.pages.verify_email.header') }}</h1>
            </div>

            <div class="mb-4 small text-muted text-center">
                {{ __('domains/auth.pages.verify_email.subheader') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success mb-4 text-center">
                    {{ __('domains/auth.pages.verify_email.resend_link') }}
                </div>
            @endif

            <div class="mt-4 d-flex justify-content-between align-items-center">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        {{ __('domains/auth.fields.verify_email.submit') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-muted p-0 text-decoration-none">
                        {{ __('domains/auth.fields.verify_email.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layout.guest>

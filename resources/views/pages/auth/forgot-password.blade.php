<x-layout.guest>
    <div class="card" style="max-width:420px; width:100%;">
        <div class="card-body p-5">
            <div class="mb-3 text-center">
                <a href="/" class="d-inline-block mb-4"><img src="./assets/images/logo-icon.svg" alt=""
                        width="36">
                    <span class="ms-2"> <img src="./assets/images/logo.svg" alt=""></span>
                </a>
                <h1 class="card-title h5 mb-5">{{ __('domains/auth.pages.forgot_password.header') }}</h1>
            </div>

            <div class="small text-muted mb-4 text-center">
                {{ __('domains/auth.pages.forgot_password.subheader') }}
            </div>

            <!-- Session Status -->
            {{-- <x-auth-session-status class="mb-4" :status="session('status')" /> --}}

            <form method="POST" action="{{ route('password.email') }}" class="needs-validation mt-3" novalidate>
                @csrf

                <x-form.input name="email" :label="__('domains/auth.fields.forgot_password.email')" type="email" placeholder="name@example.com"
                    :value="old('email')" required autofocus />

                <button class="btn btn-primary w-100 mt-4" type="submit">{{ __('domains/auth.fields.forgot_password.submit') }}</button>
            </form>

            <div class="small text-muted mt-3 text-center">
                {{ __('domains/auth.pages.forgot_password.back_to_login') }} <a href="{{ route('login') }}" class="link-primary">{{ __('domains/auth.fields.login.submit') }}</a>
            </div>
        </div>
    </div>
</x-layout.guest>

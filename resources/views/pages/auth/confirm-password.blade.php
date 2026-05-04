<x-layout.guest>
    <div class="card" style="max-width:420px; width:100%;">
        <div class="card-body p-5">
            <div class="mb-3 text-center">
                <a href="/" class="d-inline-block mb-4"><img src="./assets/images/logo-icon.svg" alt=""
                        width="36">
                    <span class="ms-2"> <img src="./assets/images/logo.svg" alt=""></span>
                </a>
                <h1 class="card-title h5 mb-5">{{ __('domains/auth.pages.confirm_password.header') }}</h1>
            </div>

            <div class="mb-4 small text-muted text-center">
                {{ __('domains/auth.pages.confirm_password.subheader') }}
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="needs-validation mt-3" novalidate>
                @csrf

                <x-form.input name="password" :label="__('domains/auth.fields.confirm_password.password')" type="password" placeholder="Password" required autocomplete="current-password" />

                <button class="btn btn-primary w-100 mt-4" type="submit">{{ __('domains/auth.fields.confirm_password.submit') }}</button>
            </form>
        </div>
    </div>
</x-layout.guest>

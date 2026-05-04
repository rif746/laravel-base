<x-layout.guest>
    <div class="card" style="max-width:420px; width:100%;">
        <div class="card-body p-5">
            <div class="mb-3 text-center">
                <a href="/" class="d-inline-block mb-4"><img src="./assets/images/logo-icon.svg" alt=""
                        width="36">
                    <span class="ms-2"> <img src="./assets/images/logo.svg" alt=""></span>
                </a>
                <h1 class="card-title h5 mb-5">{{ __('domains/auth.pages.register.header') }}</h1>
            </div>

            <form method="POST" action="{{ route('register') }}" class="needs-validation mt-3" novalidate>
                @csrf

                <x-form.input name="name" :label="__('domains/auth.fields.register.name')" type="text" placeholder="Name" :value="old('name')" required autofocus autocomplete="name" />

                <x-form.input name="email" :label="__('domains/auth.fields.register.email')" type="email" placeholder="name@example.com" :value="old('email')" required autocomplete="username" />

                <x-form.input name="password" :label="__('domains/auth.fields.register.password')" type="password" placeholder="Password" required autocomplete="new-password" />

                <x-form.input name="password_confirmation" :label="__('domains/auth.fields.register.confirm_password')" type="password" placeholder="Confirm Password" required autocomplete="new-password" />

                <button class="btn btn-primary w-100 mt-4" type="submit">{{ __('domains/auth.fields.register.submit') }}</button>
            </form>

            <div class="small text-muted mt-3 text-center">
                {{ __('domains/auth.pages.register.has_account') }} <a href="{{ route('login') }}" class="link-primary">{{ __('domains/auth.pages.register.login_link') }}</a>
            </div>
        </div>
    </div>
</x-layout.guest>

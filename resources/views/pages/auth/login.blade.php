<x-layout.guest>
    <div class="card" style="max-width:420px; width:100%;">
        <div class="card-body p-5">
            <div class="mb-3 text-center">
                <a href="index.html" class="d-inline-block mb-4"><img src="./assets/images/logo-icon.svg" alt=""
                        width="36">
                    <span class="ms-2"> <img src="./assets/images/logo.svg" alt=""></span>
                </a>
                <h1 class="card-title h5 mb-5">{{ __('domains/auth.pages.login.header') }}</h1>

            </div>

            <form class="needs-validation mt-3" method="POST" action="{{ route('login') }}" novalidate>
                @csrf
                <x-form.input name="email" :label="__('domains/auth.fields.login.email')" type="email" placeholder="name@example.com"
                    required autofocus />

                <x-form.input name="password" :label="__('domains/auth.fields.login.password')" type="password" placeholder="Password" required
                    minlength="6" />

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <x-form.checkbox name="remember" :label="__('domains/auth.fields.login.remember')" />
                    <a href="{{ route('password.request') }}" class="small link-primary">{{ __('domains/auth.fields.login.forgot_password') }}</a>
                </div>

                <button class="btn btn-primary w-100" type="submit">{{ __('domains/auth.fields.login.submit') }}</button>
            </form>

            <div class="small text-muted mt-3 text-center">
                {{ __('domains/auth.pages.login.no_account') }} <a href="{{ route('register') }}" class="link-primary">{{ __('domains/auth.pages.login.register_link') }}</a>
            </div>
        </div>
    </div>
</x-layout.guest>

<x-layout.guest>
    <div class="card" style="max-width:420px; width:100%;">
        <div class="card-body p-5">
            <div class="mb-3 text-center">
                <a href="/" class="d-inline-block mb-4"><img src="./assets/images/logo-icon.svg" alt=""
                        width="36">
                    <span class="ms-2"> <img src="./assets/images/logo.svg" alt=""></span>
                </a>
                <h1 class="card-title h5 mb-5">{{ __('domains/auth.pages.reset_password.header') }}</h1>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="needs-validation mt-3" novalidate>
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <x-form.input name="email" :label="__('domains/auth.fields.reset_password.email')" type="email" placeholder="name@example.com" :value="old('email', $request->email)" required autofocus autocomplete="username" />

                <x-form.input name="password" :label="__('domains/auth.fields.reset_password.password')" type="password" placeholder="New Password" required autocomplete="new-password" />

                <x-form.input name="password_confirmation" :label="__('domains/auth.fields.reset_password.confirm_password')" type="password" placeholder="Confirm Password" required autocomplete="new-password" />

                <button class="btn btn-primary w-100 mt-4" type="submit">{{ __('domains/auth.fields.reset_password.submit') }}</button>
            </form>
        </div>
    </div>
</x-layout.guest>

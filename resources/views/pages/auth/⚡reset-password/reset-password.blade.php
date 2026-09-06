<div class="min-vh-100 d-flex flex-column align-items-center justify-content-center p-3">
    <div class="card border border-2 border-body-tertiary shadow-sm rounded-4 w-100 overflow-hidden my-auto" style="max-width: 420px;">
        <div class="card-body p-4 p-sm-5 text-center d-flex flex-column gap-3 justify-content-center align-items-center">

            <!-- Header Brand & Auth Badge (Logo | Key Icon) -->
            <div class="d-inline-flex align-items-center justify-content-center gap-3 bg-body-tertiary border border-body-tertiary px-3 py-2 rounded-pill">
                <a href="{{ url('/') }}" class="d-inline-flex align-items-center transition-all hover-opacity-80">
                    <img src="@logoPath" alt="{{ config('app.name') }}" width="32" height="32" class="img-fluid" />
                </a>

                <div class="vr opacity-25" style="height: 35px;"></div>

                <div class="text-primary d-inline-flex align-items-center justify-content-center">
                    <x-tabler-key width="26" height="26" stroke-width="1.75" />
                </div>
            </div>

            <div class="w-100 text-start">
                <!-- Page Title & Subtitle -->
                <div class="text-center mb-3">
                    <h1 class="fw-bold text-body fs-4 mb-1">
                        {{ __($title ?? config('seotools.meta.defaults.title')) }}
                    </h1>
                    <p class="text-body-secondary small mb-0">
                        {{ __('domains/auth/pages.reset_password.subtitle') }}
                    </p>
                </div>

                <!-- Session Status Alert -->
                @if (session()->has('status'))
                    <div class="alert alert-success-subtle text-success-emphasis border border-success-subtle rounded-3 small mb-3 py-2 px-3" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Reset Password Form -->
                <form wire:submit="resetPassword" class="needs-validation d-flex flex-column gap-3" novalidate>
                    <x-form.input
                        name="form.email"
                        wire:model="form.email"
                        :label="__('domains/auth/field.reset_password.email')"
                        type="email"
                        :placeholder="__('domains/auth/field.register.email_placeholder')"
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <x-form.input
                        name="form.password"
                        wire:model="form.password"
                        :label="__('domains/auth/field.reset_password.password')"
                        type="password"
                        :placeholder="__('domains/auth/field.reset_password.password')"
                        required
                        autocomplete="new-password"
                    />

                    <x-form.input
                        name="form.password_confirmation"
                        wire:model="form.password_confirmation"
                        :label="__('domains/auth/field.reset_password.confirm_password')"
                        type="password"
                        :placeholder="__('domains/auth/field.reset_password.confirm_password')"
                        required
                        autocomplete="new-password"
                    />

                    <x-button
                        wire:loading.attr="disabled"
                        type="submit"
                        :label="__('domains/auth/field.reset_password.submit')"
                        class="w-100 py-2 fw-medium mt-2"
                        theme="primary"
                    />
                </form>

                <!-- Back to Login Link -->
                <div class="fs-7 text-body-secondary mt-4 text-center">
                    <x-link :href="route('login')" :label="__('domains/auth/pages.reset_password.back_to_login')" theme="primary" class="fw-semibold text-decoration-none" />
                </div>
            </div>

        </div>
    </div>
</div>

<div class="min-vh-100 d-flex flex-column align-items-center justify-content-center p-3">
    <div class="card border border-2 border-body-tertiary shadow-sm rounded-4 w-100 overflow-hidden my-auto" style="max-width: 420px;">
        <div class="card-body p-4 p-sm-5 text-center d-flex flex-column gap-3 justify-content-center align-items-center">

            <!-- Header Brand & Auth Badge (Logo | User Plus Icon) -->
            <div class="d-inline-flex align-items-center justify-content-center gap-3 bg-body-tertiary border border-body-tertiary px-3 py-2 rounded-pill">
                <a href="{{ url('/') }}" class="d-inline-flex align-items-center transition-all hover-opacity-80">
                    <img src="@logoPath" alt="{{ config('app.name') }}" width="32" height="32" class="img-fluid" />
                </a>

                <div class="vr opacity-25" style="height: 35px;"></div>

                <div class="text-primary d-inline-flex align-items-center justify-content-center">
                    <x-tabler-user-plus width="26" height="26" stroke-width="1.75" />
                </div>
            </div>

            <div class="w-100 text-start">
                <!-- Page Title & Subtitle -->
                <div class="text-center mb-3">
                    <h1 class="fw-bold text-body fs-4 mb-1">
                        {{ __($title ?? config('seotools.meta.defaults.title')) }}
                    </h1>
                    <p class="text-body-secondary small mb-0">
                        {{ __('domains/auth/pages.register.welcome_subtitle') }}
                    </p>
                </div>

                <!-- Session Status Alert -->
                @if (session()->has('status'))
                    <div class="alert alert-success-subtle text-success-emphasis border border-success-subtle rounded-3 small mb-3 py-2 px-3" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Register Form -->
                <form class="needs-validation d-flex flex-column gap-3" wire:submit="register" novalidate>
                    <x-form.input
                        name="form.name"
                        :label="__('domains/auth/field.register.name')"
                        wire:model.blur="form.name"
                        type="text"
                        :placeholder="__('domains/auth/field.register.name')"
                        required
                        autofocus
                        autocomplete="name"
                    />

                    <x-form.input
                        name="form.email"
                        :label="__('domains/auth/field.register.email')"
                        wire:model.blur="form.email"
                        type="email"
                        :placeholder="__('domains/auth/field.register.email_placeholder')"
                        required
                        autocomplete="username"
                    />

                    <x-form.input
                        name="form.password"
                        :label="__('domains/auth/field.register.password')"
                        wire:model.blur="form.password"
                        type="password"
                        :placeholder="__('domains/auth/field.register.password')"
                        required
                        autocomplete="new-password"
                    />

                    <x-form.input
                        name="form.password_confirmation"
                        :label="__('domains/auth/field.register.confirm_password')"
                        wire:model.blur="form.password_confirmation"
                        type="password"
                        :placeholder="__('domains/auth/field.register.confirm_password')"
                        required
                        autocomplete="new-password"
                    />

                    <x-button
                        wire:loading.attr="disabled"
                        theme="primary"
                        class="w-100 py-2 fw-medium mt-2"
                        :label="__('domains/auth/field.register.submit')"
                        type="submit"
                    />
                </form>

                <!-- Login Link -->
                <div class="fs-7 text-body-secondary mt-4 text-center">
                    {{ __('domains/auth/pages.register.has_account') }}
                    <x-link :href="route('login')" :label="__('domains/auth/pages.register.login_link')" theme="primary" class="fw-semibold text-decoration-none" />
                </div>
            </div>

        </div>
    </div>
</div>

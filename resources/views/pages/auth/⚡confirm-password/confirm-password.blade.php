<div class="vh-100 d-flex flex-column align-items-center justify-content-center p-3 overflow-hidden">
    <div class="card border border-2 border-body-tertiary shadow-sm rounded-4 w-100 overflow-hidden" style="max-width: 420px;">
        <div class="card-body p-4 p-sm-5 text-center d-flex flex-column gap-3 justify-content-center align-items-center">

            <!-- Header Brand & Auth Badge (Logo | Lock Icon) -->
            <div class="d-inline-flex align-items-center justify-content-center gap-3 bg-body-tertiary border border-body-tertiary px-3 py-2 rounded-pill">
                <a href="{{ url('/') }}" class="d-inline-flex align-items-center transition-all hover-opacity-80">
                    <img src="@logoPath" alt="{{ config('app.name') }}" width="32" height="32" class="img-fluid" />
                </a>

                <div class="vr opacity-25" style="height: 35px;"></div>

                <div class="text-primary d-inline-flex align-items-center justify-content-center">
                    <x-tabler-lock-check width="26" height="26" stroke-width="1.75" />
                </div>
            </div>

            <div class="w-100 text-start">
                <!-- Page Title & Subheader -->
                <div class="text-center mb-3">
                    <h1 class="fw-bold text-body fs-4 mb-1">
                        {{ __($title ?? config('seotools.meta.defaults.title')) }}
                    </h1>
                    <p class="text-body-secondary small mb-0">
                        {{ __('domains/auth/pages.confirm_password.subheader') }}
                    </p>
                </div>

                <!-- Session Status Alert -->
                @if (session()->has('status'))
                    <div class="alert alert-success-subtle text-success-emphasis border border-success-subtle rounded-3 small mb-3 py-2 px-3" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Confirm Password Form -->
                <form wire:submit="confirmPassword" class="needs-validation d-flex flex-column gap-3" novalidate>
                    <x-form.input
                        name="form.password"
                        wire:model="form.password"
                        :label="__('domains/auth/field.confirm_password.password')"
                        type="password"
                        :placeholder="__('domains/auth/field.confirm_password.password')"
                        required
                        autocomplete="current-password"
                        autofocus
                    />

                    <x-button
                        wire:loading.attr="disabled"
                        theme="primary"
                        class="w-100 py-2 fw-medium mt-2"
                        :label="__('domains/auth/field.confirm_password.submit')"
                        type="submit"
                    />
                </form>
            </div>

        </div>
    </div>
</div>

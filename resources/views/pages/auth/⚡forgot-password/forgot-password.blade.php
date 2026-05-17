<div>
    <div class="small text-muted mb-4 text-center">
        {{ __('domains/auth.pages.forgot_password.subheader') }}
    </div>

    <!-- Session Status -->
    {{-- <x-auth-session-status class="mb-4" :status="session('status')" /> --}}

    <form wire:submit="forgotPassword" class="needs-validation mt-3" novalidate>
        <x-form.input name="email" :label="__('domains/auth.fields.forgot_password.email')" type="email" placeholder="name@example.com" wire:model="email"
            required autofocus />

        <x-button type="submit" :label="__('domains/auth.fields.forgot_password.submit')" class="w-100" theme="primary" />
    </form>

    <div class="small text-muted mt-3 text-center">
        {{ __('domains/auth.pages.forgot_password.back_to_login') }} <x-link :href="route('login')" :label="__('domains/auth.fields.login.submit')"
            theme="primary" />
    </div>
</div>

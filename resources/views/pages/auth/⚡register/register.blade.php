<div>
    <form class="needs-validation mt-3" wire:submit="register" novalidate>

        <x-form.input name="form.name" :label="__('domains/auth.fields.register.name')" wire:model.blur="form.name" type="text" placeholder="Name" required
            autofocus autocomplete="name" />

        <x-form.input name="form.email" :label="__('domains/auth.fields.register.email')" wire:model.blur="form.email" type="email"
            placeholder="name@example.com" required autocomplete="username" />

        <x-form.input name="form.password" :label="__('domains/auth.fields.register.password')" wire:model.blur="form.password" type="password"
            placeholder="Password" required autocomplete="new-password" />

        <x-form.input name="form.password_confirmation" :label="__('domains/auth.fields.register.confirm_password')" wire:model.blur="form.password_confirmation"
            type="password" placeholder="Confirm Password" required autocomplete="new-password" />

        <x-button theme="primary" class="w-100" label="{{ __('domains/auth.fields.register.submit') }}" type="submit"
            wire:loading />
    </form>
    <div class="small text-muted mt-3 text-center">
        {{ __('domains/auth.pages.register.has_account') }} <x-link href="{{ route('login') }}" :label="__('domains/auth.pages.register.login_link')"
            theme="primary" />
    </div>
</div>

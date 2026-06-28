<div>
    <form class="needs-validation mt-3 d-flex flex-column gap-3" wire:submit="login" novalidate>

        <x-form.input name="form.email" wire:model="form.email" :label="__('domains/auth/field.login.email')" type="email" placeholder="name@example.com"
            required autofocus />

        <x-form.input name="form.password" wire:model="form.password" :label="__('domains/auth/field.login.password')" type="password" :placeholder="__('domains/auth/field.login.password')"
            required minlength="6" />

        <div class="d-flex justify-content-between align-items-center">
            <x-form.checkbox name="form.remember" wire:model="form.remember" :label="__('domains/auth/field.login.remember')" />
            <x-link :href="route('password.request')" :label="__('domains/auth/field.login.forgot_password')" theme="primary" />
        </div>

        <x-button wire:loading theme="primary" :label="__('domains/auth/field.login.submit')" class="w-100" type="submit" />
    </form>

    <div class="small text-muted mt-3 text-center">
        {{ __('domains/auth/pages.login.no_account') }} <x-link :href="route('register')" :label="__('domains/auth/pages.login.register_link')" theme="primary" />
    </div>
</div>

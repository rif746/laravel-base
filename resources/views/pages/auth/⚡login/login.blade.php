<div>
    <form class="needs-validation mt-3" wire:submit="login" novalidate>
        <x-form.input name="email" wire:model="email" :label="__('domains/auth.fields.login.email')" type="email" placeholder="name@example.com"
            required autofocus />
        <x-form.input name="password" wire:model="password" :label="__('domains/auth.fields.login.password')" type="password" placeholder="Password"
            required minlength="6" />

        <div class="d-flex justify-content-between align-items-center mb-3">
            <x-form.checkbox name="remember" wire:model="remember" :label="__('domains/auth.fields.login.remember')" />
            <x-link :href="route('password.request')" :label="__('domains/auth.fields.login.forgot_password')" theme="primary" />
        </div>

        <x-button wire:loading theme="primary" :label="__('domains/auth.fields.login.submit')" class="w-100" type="submit" />
    </form>

    <div class="small text-muted mt-3 text-center">
        {{ __('domains/auth.pages.login.no_account') }} <x-link :href="route('register')" :label="__('domains/auth.pages.login.register_link')" theme="primary" />
    </div>
</div>

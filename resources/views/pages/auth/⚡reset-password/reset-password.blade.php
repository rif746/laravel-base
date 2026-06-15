<div>
    <form wire:submit="resetPassword" class="needs-validation mt-3" novalidate>
        <x-form.input name="form.email" wire:model="form.email" :label="__('domains/auth.fields.reset_password.email')" type="email" placeholder="name@example.com"
            required autofocus autocomplete="username" />

        <x-form.input name="form.password" wire:model="form.password" :label="__('domains/auth.fields.reset_password.password')" type="password" :placeholder="__('domains/auth.fields.reset_password.password')"
            required autocomplete="new-password" />

        <x-form.input name="form.password_confirmation" wire:model="form.password_confirmation" :label="__('domains/auth.fields.reset_password.confirm_password')" type="password"
            :placeholder="__('domains/auth.fields.reset_password.confirm_password')" required autocomplete="new-password" />

        <x-button type="submit" :label="__('domains/auth.fields.reset_password.submit')" class="w-100" theme="primary" />
    </form>
</div>

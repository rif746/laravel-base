<div>
    <form wire:submit="resetPassword" class="needs-validation mt-3" novalidate>
        <x-form.input name="email" wire:model="email" :label="__('domains/auth.fields.reset_password.email')" type="email" placeholder="name@example.com"
            required autofocus autocomplete="username" />

        <x-form.input name="password" wire:model="password" :label="__('domains/auth.fields.reset_password.password')" type="password" placeholder="New Password"
            required autocomplete="new-password" />

        <x-form.input name="password_confirmation" wire:model="password_confirmation" :label="__('domains/auth.fields.reset_password.confirm_password')" type="password"
            placeholder="Confirm Password" required autocomplete="new-password" />

        <x-button type="submit" :label="__('domains/auth.fields.reset_password.submit')" class="w-100" theme="primary" />
    </form>
</div>

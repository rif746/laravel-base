<div>
    <div class="small text-muted mb-4 text-center">
        {{ __('domains/auth.pages.confirm_password.subheader') }}
    </div>

    <form wire:submit="confirmPassword" class="needs-validation mt-3" novalidate>

        <x-form.input name="password" wire:model="password" :label="__('domains/auth.fields.confirm_password.password')" type="password" placeholder="Password"
            required autocomplete="current-password" />

        <x-button wire:loading theme="primary" class="w-100 mt-4" :label="__('domains/auth.fields.confirm_password.submit')" type="submit" />
    </form>
</div>

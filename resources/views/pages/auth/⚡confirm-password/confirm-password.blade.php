<div>
    <div class="small text-muted mb-4 text-center">{{ __('domains/auth/pages.confirm_password.subheader') }}</div>

    <form wire:submit="confirmPassword" class="needs-validation d-flex flex-column mt-3 gap-3" novalidate>
        <x-form.input
            name="form.password"
            wire:model="form.password"
            :label="__('domains/auth/field.confirm_password.password')"
            type="password"
            :placeholder="__('domains/auth/field.confirm_password.password')"
            required
            autocomplete="current-password"
        />

        <x-button
            wire:loading
            theme="primary"
            class="mt-4 w-100"
            :label="__('domains/auth/field.confirm_password.submit')"
            type="submit"
        />
    </form>
</div>

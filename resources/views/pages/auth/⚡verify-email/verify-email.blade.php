<div wire:poll.10s="checkVerified">
    <div class="small text-muted mb-4 text-center">
        {{ __('domains/auth.pages.verify_email.subheader') }}
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <form wire:submit="resendEmail">
            <x-button type="submit" theme="primary">
                {{ __('domains/auth.fields.verify_email.submit') }}
            </x-button>
        </form>

        <form wire:submit="logout">
            <x-button type="submit" theme="danger">
                {{ __('ui.button.logout') }}
            </x-button>
        </form>
    </div>
</div>

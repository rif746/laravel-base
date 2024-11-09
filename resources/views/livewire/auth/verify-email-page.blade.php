<x-card title="Verify Email" subtitle="Verify your email before using this system." shadow separator>
    <span>
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </span>

    <div class="flex justify-between items-center mt-3">
        <x-button wire:click="resendEmail">
            {{ __('Resend Verification Email') }}
        </x-button>
        <x-button wire:click="logout">
            {{ __('Log Out') }}
        </x-button>
    </div>
</x-card>

<div>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="$status" />

    <form wire:submit="send_reset">
        @csrf
        <!-- Email Address -->
        <x-group.form.line-input type="email" wire:model="email" label="Email" />

        <div class="flex items-center justify-end mt-4">
            <x-element.button.primary>
                {{ __('Email Password Reset Link') }}
            </x-element.button.primary>
        </div>
    </form>
</div>

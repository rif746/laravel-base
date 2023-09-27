<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-group.form.line-input label="Password" type="password" name="password" />
        </div>

        <div class="flex justify-end mt-4">
            <x-element.button.primary>
                {{ __('Confirm') }}
            </x-element.button.primary>
        </div>
    </form>
</x-guest-layout>

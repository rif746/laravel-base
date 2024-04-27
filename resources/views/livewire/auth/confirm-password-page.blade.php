<div>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" wire:submit="send">
        @csrf

        <!-- Password -->
        <div>
            <x-element.layout.vertical name="password" label="Password">
                <x-element.input.line type="password" wire:model="password" />
            </x-element.layout.vertical>
        </div>

        <div class="flex justify-end mt-4">
            <x-element.button.primary>
                {{ __('Confirm') }}
            </x-element.button.primary>
        </div>
    </form>
</div>

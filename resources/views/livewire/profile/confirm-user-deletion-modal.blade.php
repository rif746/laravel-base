<x-container.modal :name="$this->modal_name" :title="$this->title" method="save">
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Are you sure you want to delete your account?') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
    </p>

    <div class="mt-6">
        <x-group.form.line-input type="password" wire:model="current_password" label="Current Password" />
    </div>
    <x-slot:button>
        <x-element.button.danger type="submit">Delete</x-element.button.danger>
    </x-slot:button>
</x-container.modal>

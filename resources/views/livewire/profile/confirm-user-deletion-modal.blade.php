<div wire:modal>
    <x-modal wire:model="modal" :title="__('locale/profile.text.user_deletion_ask')" class="backdrop-blur" persistent>
        <x-hr target="save,load" />

        <x-form wire:submit.prevent="save">
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('locale/profile.text.user_deletion_alert') }}
            </p>

            <div class="mt-6">
                <x-password :label="__('Current Password')" wire:model="current_password" />
            </div>

            <x-slot:actions>
                <x-button label="Cancel" wire:click="$toggle('modal')" />
                <x-button class="bg-red-600 hover:bg-red-500 text-white" type="submit" icon="o-trash"
                    :label="__('locale/profile.button.delete_account')" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>

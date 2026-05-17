<x-modal id="update-profile-modal" :title="$this->title" wire:submit="save" wire:loading form livewire>
    <x-form.input name="name" :label="__('domains/identity.fields.user.name')" wire:model="name" />
    <x-form.input name="email" :label="__('domains/identity.fields.user.email')" wire:model="email" />
    <x-form.select name="gender" :label="__('domains/account.fields.profile.gender')" wire:model="gender">
        @foreach (\App\Enums\Identity\GenderOption::cases() as $case)
            <option value="{{ $case->value }}">{{ $case->label() }}</option>
        @endforeach
    </x-form.select>
    <x-form.input name="date_of_birth" type="date" :label="__('domains/account.fields.profile.date_of_birth')" wire:model="date_of_birth" />
    <x-form.input name="phone_number" type="number" :label="__('domains/account.fields.profile.phone_number')" wire:model="phone_number" />

    <x-slot:footer>
        <x-button type="button" data-bs-dismiss="modal" :label="__('ui.button.close')" />
        <x-button type="submit" theme="primary" :label="__('ui.button.save')" />
    </x-slot:footer>
</x-modal>

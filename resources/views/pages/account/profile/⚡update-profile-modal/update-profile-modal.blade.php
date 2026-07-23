@php use App\Domains\Account\Enums\GenderOption; @endphp
<x-modal id="update-profile-modal" :title="$this->title" wire:submit="save" wire:loading form livewire>

    <div class="d-flex flex-column gap-3">
        <x-form.input name="form.name" :label="__('domains/identity/field.user.name')" wire:model="form.name" />

        <x-form.input name="form.email" :label="__('domains/identity/field.user.email')" wire:model="form.email" />

        <x-form.select name="form.gender" :label="__('domains/account/field.profile.gender')" wire:model="form.gender">
            @foreach (GenderOption::cases() as $case)
                <option value="{{ $case->value }}">{{ $case->label() }}</option>
            @endforeach
        </x-form.select>

        <x-form.input name="form.date_of_birth" type="date" :label="__('domains/account/field.profile.date_of_birth')"
            wire:model="form.date_of_birth" />

        <x-form.input name="form.phone_number" type="number" :label="__('domains/account/field.profile.phone_number')"
            wire:model="form.phone_number" />
    </div>

    <x-slot:footer>
        <x-button type="button" data-bs-dismiss="modal" :label="__('ui/button.close')" />
        <x-button type="submit" theme="primary" :label="__('ui/button.save')" />
    </x-slot:footer>
</x-modal>

<x-card :title="__('domains/account/sections.account.user_settings.title')" :subtitle="__('domains/account/sections.account.user_settings.description')">
    <x-slot:actions>
        <x-button icon="tabler-device-floppy" theme="primary" size="sm" rounded class="btn-icon" wire:click="save" disabled wire:dirty.attr.remove="disabled"
            wire:loading />
    </x-slot:actions>

    <div class="row g-3">
        @foreach ($settings as $setting)
            <div class="col-12 col-md-6">
                <div class="form-floating">
                    <select class="form-select"
                            id="setting_{{ $setting['key'] }}"
                            aria-label="{{ $setting['label'] }}"
                            wire:model="form.{{ $setting['key'] }}">
                        @foreach ($setting['options'] as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    <label for="setting_{{ $setting['key'] }}" class="fw-semibold">{{ $setting['label'] }}</label>
                </div>
            </div>
        @endforeach
    </div>
</x-card>

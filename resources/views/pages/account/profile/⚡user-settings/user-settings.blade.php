<x-card :title="__('domains/account/pages.user_settings.title')" :subtitle="__('domains/account/pages.user_settings.description')">
    <x-slot:actions>
        <x-button icon="tabler-device-floppy" theme="primary" size="sm" rounded class="btn-icon" wire:click="save" disabled wire:dirty.attr.remove="disabled"
            wire:loading />
    </x-slot:actions>
    <div class="row gy-4">
        @foreach ($settings as $setting)
            <div class="col-12">
                <div class="d-flex flex-column gap-2">
                    <span class="font-bold">{{ $setting['label'] }}</span>
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        @foreach ($setting['options'] as $key => $value)
                            <input type="radio" class="btn-check" name="{{ $setting['key'] }}"
                                id="{{ $setting['key'] }}_{{ $key }}" autocomplete="off"
                                value="{{ $key }}" wire:model="form.{{ $setting['key'] }}">
                            <label
                                class="btn btn-outline-primary"
                                for="{{ $setting['key'] }}_{{ $key }}">{{ $value }}</label>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-card>

<x-card :title="__('domains/account.pages.user_settings.title')" :subtitle="__('domains/account.pages.user_settings.description')">
    <x-slot:actions>
        <x-button icon="tabler-device-floppy" theme="primary" size="sm" rounded class="btn-icon" wire:click="save" disabled wire:dirty.attr.remove="disabled"
            wire:loading />
    </x-slot:actions>
    <div>
        @foreach ($settings as $setting)
            <div class="row">
                <div class="col-sm-12 col-md-4 fw-bold py-1">
                    <span>{{ $setting['label'] }}</span>
                </div>
                <div class="col-sm-12 col-md-8 py-1">
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        @foreach ($setting['options'] as $key => $value)
                            <div>
                                <input type="radio" class="btn-check" name="{{ $setting['key'] }}"
                                    id="{{ $setting['key'] }}_{{ $key }}" autocomplete="off"
                                    value="{{ $key }}" wire:model="form.{{ $setting['key'] }}">
                                <label
                                    class="btn btn-outline-primary rounded-0 @if ($loop->first) rounded-start @elseif($loop->last) rounded-end @else border-start border-start-0 @endif"
                                    for="{{ $setting['key'] }}_{{ $key }}">{{ $value }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-card>

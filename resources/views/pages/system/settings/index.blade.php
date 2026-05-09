@use(\App\Enums\System\SystemSettingKey)
<x-layout.app>
    @foreach (SystemSettingKey::section() as $section => $items)
        <x-card :title="$section" class="mb-3">
            <div class="row">
                @foreach ($items as $item)
                    <div class="col-sm-12 col-md-6" x-data="updateSetting('{{ $item->value }}', '{{ $settings[$item->value] }}')">
                        <template x-if="editMode">
                            <div class="d-flex flex-column mb-3 border p-2">
                                @if ($item->isImage())
                                    <x-form.input :label="$item->label()" type="file" x-model="value" :name="$item->value" />
                                @else
                                    <x-form.input :label="$item->label()" type="text" x-model="value" :name="$item->value"
                                        :value="old($item->value)" />
                                @endif
                                <x-button x-on:click="save" icon="tabler-device-floppy" size="sm" :icon-property="['width' => '14px']"
                                    icon-only theme="primary" class="align-self-end" />
                            </div>
                        </template>
                        <template x-if="!editMode">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <label for="{{ $item->value }}" class="form-label">{{ $item->label() }}</label>
                                    <x-button x-on:click="editMode = true" icon="tabler-pencil" size="sm"
                                        :icon-property="['width' => '14px']" icon-only />
                                </div>
                                <p x-text="value"></p>
                            </div>
                        </template>
                    </div>
                @endforeach
            </div>
        </x-card>
    @endforeach

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('updateSetting', (key, value) => ({
                    key: key,
                    value: value,
                    editMode: false,
                    async save() {
                        const formData = new FormData();
                        formData.append('key', this.key);
                        formData.append('value', this.value);
                        this.$put(this.$route('settings.update'), formData)
                            .then(() => {
                                this.editMode = false;
                                toast('success', 'Settings updated successfully')
                            })
                            .catch(() => toast('error', 'Failed to update settings'));
                    }
                }))
            })
        </script>
    @endpush
</x-layout.app>

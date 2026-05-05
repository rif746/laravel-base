<x-card :title="__('domains/account.pages.user_settings.title')" :subtitle="__('domains/account.pages.user_settings.description')" x-data="settings" x-init="loadData">
    <x-slot:actions>
        <x-button icon="tabler-device-floppy" theme="primary" size="sm" x-bind:disabled="!isDirty" rounded
            class="btn-icon" x-on:click="save" />
    </x-slot:actions>
    <div class="row">
        <div class="position-absolute justify-content-center align-items-center bottom-0 end-0 top-0"
            style="z-index: 9999;background-color: rgba(0,0,0,0.48);" x-bind:class="{ 'd-flex': loading }" x-cloak
            x-show="loading">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <div>
        <template x-for="setting in settings" :key="setting.key">
            <div class="row">
                <div class="col-sm-12 col-md-4 fw-bold py-1">
                    <span x-text="setting.label"></span>
                </div>
                <div class="col-sm-12 col-md-8 py-1">
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <template x-for="(option, i) in setting.options" :key="option.key">
                            <div>
                                <input type="radio" class="btn-check" :name="setting.key"
                                    :id="`${setting.key}_${option.key}`" autocomplete="off" :value="option.key"
                                    x-model="form[setting.key]">
                                <label class="btn btn-outline-primary rounded-0"
                                    x-bind:class="{
                                        'rounded-start': i === 0,
                                        'rounded-end': i === setting.options.length - 1,
                                        'border-start border-start-0': i !== 0
                                    }"
                                    :for="`${setting.key}_${option.key}`" x-text="option.label"></label>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-card>
@push('scripts')
    <script>
        document.addEventListener('alpine:init', function() {
            Alpine.data('settings', () => ({
                settings: {},
                form: {},
                form_original: {},
                loading: false,
                get isDirty() {
                    return JSON.stringify(this.form) !== this.form_original;
                },
                loadData() {
                    this.loading = true;
                    this.$get(this.$route('profile.settings.index'))
                        .then((response) => {
                            this.settings = response.data.data;
                            this.form = response.data.data.reduce((prev, item) => {
                                prev[item.key] = item.val;
                                return prev;
                            }, {});
                            this.form_original = JSON.stringify(this.form);
                        })
                        .catch((error) => {
                            console.error(error);
                        }).finally(() => {
                            this.loading = false;
                        });
                },
                save() {
                    this.loading = true;
                    this.$patch(this.$route('profile.settings.update'), this.form)
                        .then((response) => {
                            this.loadData();
                            toast(response.data.message, 'success')
                        })
                        .catch((error) => {
                            console.error(error);
                        }).finally(() => {
                            this.loading = false;
                        });
                }
            }));
        });
    </script>
@endpush

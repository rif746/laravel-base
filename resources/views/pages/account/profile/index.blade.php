<x-layout.app>
    <div class="row gap-2">
        <div class="col-sm-8 mx-auto" x-data="profile" x-init="loadData" x-on:profile-reload.window="loadData">
            <x-card :title="__('domains/account.pages.profile.title')" :subtitle="__('domains/account.pages.profile.description')">
                <x-slot:actions>
                    <x-button icon="tabler-lock" theme="warning" size="sm" rounded class="btn-icon"
                        data-bs-toggle="modal" data-bs-target="#reset-password-form-modal" />
                    <x-button icon="tabler-pencil" theme="primary" size="sm" rounded class="btn-icon"
                        data-bs-toggle="modal" data-bs-target="#profile-form-modal" />
                </x-slot:actions>

                <div class="row">
                    <div class="position-absolute top-0 bottom-0 end-0 justify-content-center align-items-center"
                        style="z-index: 9999;background-color: rgba(0,0,0,0.48);" x-bind:class="{ 'd-flex': loading }"
                        x-cloak x-show="loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 py-1 fw-bold">{{ __('domains/identity.fields.user.name') }}</div>
                    <div class="col-sm-12 col-md-8 py-1"><span x-text="profile.name"></span></div>
                    <div class="col-sm-12 col-md-4 py-1 fw-bold">{{ __('domains/identity.fields.user.email') }}</div>
                    <div class="col-sm-12 col-md-8 py-1">
                        <span x-text="profile.email"></span>
                        <span class="badge bg-success ms-1"
                            x-show="profile.email_verified">{{ __('domains/identity.fields.user.verified') }}</span>
                        <span class="badge bg-danger ms-1 cursor-pointer" x-on:click="resendVerificationEmail()"
                            x-show="!profile.email_verified">{{ __('domains/identity.fields.user.unverified') }}</span>
                    </div>
                    <div class="col-sm-12 col-md-4 py-1 fw-bold">{{ __('domains/account.fields.profile.gender') }}</div>
                    <div class="col-sm-12 col-md-8 py-1"><span x-text="profile.gender"></span></div>
                    <div class="col-sm-12 col-md-4 py-1 fw-bold">
                        {{ __('domains/account.fields.profile.date_of_birth') }}</div>
                    <div class="col-sm-12 col-md-8 py-1"><span x-text="profile.date_of_birth"></span></div>
                    <div class="col-sm-12 col-md-4 py-1 fw-bold">{{ __('domains/account.fields.profile.phone_number') }}
                    </div>
                    <div class="col-sm-12 col-md-8 py-1"><span x-text="profile.phone_number"></span></div>
                </div>
            </x-card>
        </div>
        <div class="col-sm-8 mx-auto" x-data="settings" x-init="loadData">
            <x-card :title="__('domains/account.pages.user_settings.title')" :subtitle="__('domains/account.pages.user_settings.description')">
                <x-slot:actions>
                    <x-button icon="tabler-device-floppy" theme="primary" size="sm" x-bind:disabled="!isDirty"
                        rounded class="btn-icon" x-on:click="save" />
                </x-slot:actions>

                <div>
                    <div class="position-absolute top-0 bottom-0 end-0 justify-content-center align-items-center"
                        style="z-index: 9999;background-color: rgba(0,0,0,0.48);" x-bind:class="{ 'd-flex': loading }"
                        x-cloak x-show="loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <template x-for="setting in settings" :key="setting.key">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 py-1 fw-bold">
                                <span x-text="setting.label"></span>
                            </div>
                            <div class="col-sm-12 col-md-8 py-1">
                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                    <template x-for="(option, i) in setting.options" :key="option.key">
                                        <div>
                                            <input type="radio" class="btn-check" :name="setting.key"
                                                :id="`${setting.key}_${option.key}`" autocomplete="off"
                                                :value="option.key" x-model="form[setting.key]">
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
        </div>
    </div>

    @include('pages.account.profile._modal-form')
    @include('pages.account.profile._modal-reset-password')

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', function() {
                Alpine.data('profile', () => ({
                    profile: {},
                    loading: false,
                    loadData() {
                        this.loading = true;
                        this.$get(this.$route('profile.index'))
                            .then((response) => {
                                this.profile = response.data.data;
                            })
                            .catch((error) => {
                                console.error(error);
                            }).finally(() => {
                                this.loading = false;
                            });
                    }
                }));

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
</x-layout.app>

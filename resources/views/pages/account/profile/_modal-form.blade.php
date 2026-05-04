<x-modal id="profile-form-modal" :title="__('ui.title.create', ['resource' => __('resources.profile')])" x-data="profileForm" wire:submit="save" loading-state="loading" form>

    <x-form.input name="name" :label="__('domains/identity.fields.user.name')" x-model="form.name" />
    <x-form.input name="email" :label="__('domains/identity.fields.user.email')" x-model="form.email" />
    <x-form.select name="gender" :label="__('domains/account.fields.profile.gender')" x-model="form.gender" feedback>
        @foreach (\App\Enums\Identity\GenderOption::cases() as $case)
            <option value="{{ $case->value }}">{{ $case->label() }}</option>
        @endforeach
    </x-form.select>
    <x-form.input name="date_of_birth" type="date" :label="__('domains/account.fields.profile.date_of_birth')" x-model="form.date_of_birth" feedback />
    <x-form.input name="phone_number" type="number" :label="__('domains/account.fields.profile.phone_number')" x-model="form.phone_number" feedback />

    <x-slot:footer>
        <button type="button" class="btn btn-secondary" x-bind:disabled="loading"
            data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" x-bind:disabled="loading">Save changes</button>
    </x-slot:footer>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('profileForm', () => {
                    return {
                        form: {},
                        feedback: {},
                        url: '',
                        loading: false,
                        init() {
                            this.$bs.modal.on('show', (e) => {
                                this.url = this.$route('profile.update')
                                this.$bs.modal.updateHTML('.modal-title',
                                    '{{ __('ui.title.update', ['resource' => __('resources.profile')]) }}'
                                )

                                this.loading = true
                                this.$get(this.url, {
                                    params: {
                                        is_edit: true
                                    }
                                }).then((res) => {
                                    this.form = res.data.data
                                }).catch(err => {
                                    toast(err.response.data.message, 'error')
                                }).finally(() => {
                                    this.loading = false
                                })
                            })
                            this.$bs.modal.on('hide', (e) => {
                                this.form = {}
                                this.feedback = {}
                            })
                        },
                        save() {
                            this.loading = true
                            this.feedback = {}
                            this.$patch(this.url, this.form).then((res) => {
                                toast(res.data.message, 'success')
                                this.loading = false
                                this.$dispatch('profile-reload')
                                this.$bs.modal.instance(this.$refs['profile-form-modal']).hide()
                            }).catch((err) => {
                                this.feedback = this.$formErrors(err)
                                this.loading = false
                                toast(err.response.data.message, 'error')
                            })
                        }
                    }
                })
            })
        </script>
    @endpush
</x-modal>

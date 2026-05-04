<x-modal id="user-form-modal" :title="__('ui.title.create', ['resource' => __('resources.user')])" x-data="form" wire:submit="save" loading-state="loading" form>

    <x-form.input name="name" :label="__('domains/identity.fields.user.name')" x-model="form.name" />
    <x-form.input name="email" :label="__('domains/identity.fields.user.email')" x-model="form.email" />
    <x-form.select name="role_name" :label="__('domains/identity.fields.role.name')" x-model="form.role" feedback>
        <template x-for="(role, i) in roles">
            <option x-bind:value="role.label" x-text="role.label"></option>
        </template>
    </x-form.select>
    <x-form.input name="password" x-show="!isEdit" label="Password" x-model="form.password" />
    <x-form.input name="password_confirmation" x-show="!isEdit" label="Password Confirmation"
        x-model="form.password_confirmation" />

    <x-slot:footer>
        <button type="button" class="btn btn-secondary" x-bind:disabled="loading"
            data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" x-bind:disabled="loading">Save changes</button>
    </x-slot:footer>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('form', () => {
                    return {
                        form: {},
                        feedback: {},
                        isEdit: false,
                        loading: false,
                        roles: [],
                        url: '',
                        init() {
                            this.$bs.modal.on('show', (e) => {
                                this.form.id = e?.relatedTarget?.dataset?.id
                                this.isEdit = false
                                this.url = this.$route('users.store')
                                this.$bs.modal.updateHTML('.modal-title',
                                    '{{ __('ui.title.create', ['resource' => __('resources.user')]) }}'
                                )

                                this.$get(this.$route('api.v1.lookups.roles')).then(res => {
                                    this.roles = res.data.data
                                })

                                if (this.form.id) {
                                    this.isEdit = true
                                    this.loading = true
                                    this.url = this.$route('users.update', {
                                        user: this.form.id
                                    })
                                    this.$bs.modal.updateHTML('.modal-title',
                                        '{{ __('ui.title.update', ['resource' => __('resources.user')]) }}'
                                    )
                                    this.$get(this.url).then((res) => {
                                        this.form = res.data.data
                                    }).catch(err => {
                                        toast(err.response.data.message, 'error')
                                    }).finally(() => this.loading = false)
                                }
                            })
                            this.$bs.modal.on('hide', (e) => {
                                this.form = {}
                                this.feedback = {}
                            })
                        },
                        save() {
                            this.loading = true
                            this.feedback = {}
                            let send;
                            if (this.isEdit) {
                                send = this.$patch(this.url, this.form)
                            } else {
                                send = this.$post(this.url, this.form)
                            }

                            send.then((res) => {
                                toast(res.data.message, 'success')
                                this.loading = false
                                LaravelDataTables['user-table'].ajax.reload()
                                this.$bs.modal.instance(this.$refs['user-form-modal']).hide()
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

<x-modal id="role-form-modal" :title="__('ui.title.create', ['resource' => __('resources.role')])" x-data="form" wire:submit="save" size="modal-xl"
    loading-state="loading" form>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            <x-form.input name="name" :label="__('domains/identity.fields.role.name')" x-model="form.name" />
            <x-form.select name="guard_name" :label="__('domains/identity.fields.role.guard_name')" x-model="form.guard_name" feedback>
                <option value="web">Web</option>
                <option value="api">Api</option>
            </x-form.select>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="mb-3">
                <label class="form-label">{{ __('domains/identity.fields.role.permissions') }}</label>
                <div class="row gap-1">
                    <template x-for="group in permissions" :key="group.group">
                        <div class="col-md-12">
                            <div class="card shadow-none">
                                <div class="card-header bg-light py-2">
                                    <span class="fw-bold small text-uppercase" x-text="group.group"></span>
                                </div>
                                <div class="card-body py-2">
                                    <ul class="list-unstyled mb-0">
                                        <template x-for="permission in group.permissions" :key="permission.name">
                                            <li class="small d-flex align-items-start mb-1">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        :id="'permission-' + permission.name" :value="permission.name"
                                                        x-model="form.permissions">
                                                    <label class="form-check-label"
                                                        :for="'permission-' + permission.name"
                                                        x-text="permission.description"></label>
                                                </div>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

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
                        permissions: [],
                        url: '',
                        init() {
                            this.$bs.modal.on('show', (e) => {
                                this.form.id = e?.relatedTarget?.dataset?.id
                                this.isEdit = false
                                this.url = this.$route('roles.store')
                                this.$bs.modal.updateHTML('.modal-title',
                                    '{{ __('ui.title.create', ['resource' => __('resources.role')]) }}'
                                )
                                this.$get(this.$route('api.v1.lookups.permissions')).then(res => {
                                    this.permissions = res.data.data.reduce((acc, item) => {
                                        const group = acc.find(g => g.group === item
                                            .group);
                                        if (group) {
                                            group.permissions.push(item);
                                        } else {
                                            acc.push({
                                                group: item.group,
                                                permissions: [item]
                                            });
                                        }
                                        return acc;
                                    }, []);
                                })

                                if (this.form.id) {
                                    this.isEdit = true
                                    this.loading = true
                                    this.url = this.$route('roles.update', {
                                        role: this.form.id
                                    })
                                    this.$bs.modal.updateHTML('.modal-title',
                                        '{{ __('ui.title.update', ['resource' => __('resources.role')]) }}'
                                    )
                                    this.$get(this.url, {
                                        params: {
                                            is_edit: true
                                        }
                                    }).then((res) => {
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
                            this.$patch(this.url, this.form)
                                .then((res) => {
                                    toast(res.data.message, 'success')
                                    this.loading = false
                                    LaravelDataTables['role-table'].ajax.reload()
                                    this.$bs.modal.instance(this.$refs['role-form-modal']).hide()
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

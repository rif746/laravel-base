<x-modal id="role-view-modal" :title="__('ui.title.view', ['resource' => __('resources.role')])" x-data="view" size="modal-xl" loading-state="loading">

    <div class="row">

        <div class="col-sm-12 col-md-6">
            <div class="row mx-4">
                <div class="col-sm-12 col-md-4 fw-bold border-bottom px-4 py-2">{{ __('domains/identity.fields.role.name') }}</div>
                <div class="col-sm-12 col-md-8 border-bottom px-4 py-2" x-text="form.name"></div>

                <div class="col-sm-12 col-md-4 fw-bold border-bottom px-4 py-2">{{ __('domains/identity.fields.role.guard_name') }}</div>
                <div class="col-sm-12 col-md-8 border-bottom px-4 py-2" x-text="form.guard_name"></div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 mt-4">
            <div class="row g-3">
                <template x-for="group in form.permissions" :key="group.group">
                    <div class="col-md-12">
                        <div class="card shadow-none border h-100">
                            <div class="card-header bg-light py-2">
                                <span class="fw-bold small text-uppercase" x-text="group.group"></span>
                            </div>
                            <div class="card-body py-2">
                                <ul class="list-unstyled mb-0">
                                    <template x-for="permission in group.permissions" :key="permission.name">
                                        <li class="small d-flex align-items-start mb-1">
                                            <i class="ti ti-check text-success me-2 mt-1"></i>
                                            <span x-text="permission.description"></span>
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
    <x-slot:footer>
        <button type="button" class="btn btn-secondary" x-bind:disabled="loading"
            data-bs-dismiss="modal">Close</button>
    </x-slot:footer>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('view', () => {
                    return {
                        form: {},
                        url: '',
                        loading: false,
                        init() {
                            this.$bs.modal.on('show', (e) => {
                                this.form.id = e.relatedTarget.dataset.id
                                if (this.form.id) {
                                    this.loading = true
                                    this.url = this.$route('roles.show', {
                                        role: this.form.id
                                    })
                                    this.$get(this.url).then((res) => {
                                        this.form = res.data.data
                                        this.form.permissions = this.form.permissions.reduce((
                                            acc, item) => {
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
                                    }).catch((err) => {
                                        console.error(err)
                                        this.form = {}
                                    }).finally(() => {
                                        this.loading = false
                                    })
                                }
                            })
                            this.$bs.modal.on('hide', (e) => {
                                this.form = {}
                            })
                        }
                    }
                })
            })
        </script>
    @endpush
</x-modal>

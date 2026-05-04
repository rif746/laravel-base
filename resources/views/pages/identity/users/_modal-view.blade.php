<x-modal id="user-view-modal" :title="__('ui.title.view', ['resource' => __('resources.user')])" x-data="view" loading-state="loading">

    <div class="row mx-4">
        <div class="col-sm-12 col-md-4 fw-bold border-bottom px-4 py-2">{{ __('domains/identity.fields.user.name') }}</div>
        <div class="col-sm-12 col-md-8 border-bottom px-4 py-2" x-text="form.name"></div>

        <div class="col-sm-12 col-md-4 fw-bold border-bottom px-4 py-2">{{ __('domains/identity.fields.user.email') }}</div>
        <div class="col-sm-12 col-md-8 border-bottom px-4 py-2" x-text="form.email"></div>

        <div class="col-sm-12 col-md-4 fw-bold border-bottom px-4 py-2">{{ __('resources.role') }}</div>
        <div class="col-sm-12 col-md-8 border-bottom px-4 py-2" x-text="form.role"></div>
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
                                    this.url = this.$route('users.show', {
                                        user: this.form.id
                                    })
                                    this.$get(this.url).then((res) => {
                                        this.form = res.data.data
                                    }).catch((err) => {
                                        console.err(err)
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

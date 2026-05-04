<x-modal id="reset-password-form-modal" :title="__('domains/account.pages.password.title')" x-data="passwordForm" wire:submit="save" loading-state="loading"
    form>

    <x-form.input name="current_password" type="password" :label="__('domains/identity.fields.user.current_password')" x-model="form.current_password" feedback />
    <x-form.input name="password" type="password" :label="__('domains/identity.fields.user.new_password')" x-model="form.password" feedback />
    <x-form.input name="password_confirmation" type="password" :label="__('domains/identity.fields.user.password_confirmation')" x-model="form.password_confirmation"
        feedback />

    <x-slot:footer>
        <button type="button" class="btn btn-secondary" x-bind:disabled="loading"
            data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" x-bind:disabled="loading">Update Password</button>
    </x-slot:footer>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('passwordForm', () => {
                    return {
                        form: {},
                        feedback: {},
                        loading: false,
                        init() {
                            this.$bs.modal.on('hide', (e) => {
                                this.form = {}
                                this.feedback = {}
                            })
                        },
                        save() {
                            this.loading = true
                            this.feedback = {}
                            this.$put(this.$route('password.update'), this.form).then((res) => {
                                toast(res.data.message, 'success')
                                this.loading = false
                                this.$bs.modal.instance(this.$refs['reset-password-form-modal']).hide()
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

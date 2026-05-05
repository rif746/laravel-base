<x-card :title="__('domains/account.pages.profile.title')" :subtitle="__('domains/account.pages.profile.description')" x-data="profile" x-init="loadData"
    x-on:profile-reload.window="loadData">
    <x-slot:actions>
        <x-button icon="tabler-lock" theme="warning" size="sm" rounded class="btn-icon" data-bs-toggle="modal"
            data-bs-target="#reset-password-form-modal" />
        <x-button icon="tabler-pencil" theme="primary" size="sm" rounded class="btn-icon" data-bs-toggle="modal"
            data-bs-target="#profile-form-modal" />
    </x-slot:actions>

    <div class="row">
        <div class="position-absolute justify-content-center align-items-center bottom-0 end-0 top-0"
            style="z-index: 9999;background-color: rgba(0,0,0,0.48);" x-bind:class="{ 'd-flex': loading }" x-cloak
            x-show="loading">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="col-sm-12 col-md-4 fw-bold py-1">{{ __('domains/identity.fields.user.name') }}</div>
        <div class="col-sm-12 col-md-8 py-1"><span x-text="profile.name"></span></div>
        <div class="col-sm-12 col-md-4 fw-bold py-1">{{ __('domains/identity.fields.user.email') }}</div>
        <div class="col-sm-12 col-md-8 py-1">
            <span x-text="profile.email"></span>
            <span class="badge bg-success ms-1"
                x-show="profile.email_verified">{{ __('domains/identity.fields.user.verified') }}</span>
            <span class="badge bg-danger ms-1 cursor-pointer" x-on:click="resendVerificationEmail()"
                x-show="!profile.email_verified">{{ __('domains/identity.fields.user.unverified') }}</span>
        </div>
        <div class="col-sm-12 col-md-4 fw-bold py-1">{{ __('domains/account.fields.profile.gender') }}</div>
        <div class="col-sm-12 col-md-8 py-1"><span x-text="profile.gender"></span></div>
        <div class="col-sm-12 col-md-4 fw-bold py-1">
            {{ __('domains/account.fields.profile.date_of_birth') }}</div>
        <div class="col-sm-12 col-md-8 py-1"><span x-text="profile.date_of_birth"></span></div>
        <div class="col-sm-12 col-md-4 fw-bold py-1">{{ __('domains/account.fields.profile.phone_number') }}
        </div>
        <div class="col-sm-12 col-md-8 py-1"><span x-text="profile.phone_number"></span></div>
    </div>
</x-card>
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
        })
    </script>
@endpush

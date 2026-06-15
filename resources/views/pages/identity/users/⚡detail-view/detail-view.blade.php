<div class="row">
    <div class="col-md-6 col-sm-12">
        <x-card title="{{ __('domains/identity.pages.user_detail.account_info') }}">
            <x-slot:actions>
                <div class="btn-group">
                    <x-button icon="tabler-password-user" size="sm" :icon-property="['width' => 16, 'height' => 16]"
                              theme="warning" icon-only x-on:click="$ask.livewire('send-password-reset', {
                                  id: null,
                                  textMessage: '{{  __('domains/identity.pages.user_detail.confirmation.send_password_reset') }}',
                                  confirmText: '{{ __('ui.button.yes') }}',
                                  cancelText: '{{ __('ui.button.no') }}',
                              })" />
                    <x-button :icon="$this->user->status->isActive() ? 'tabler-shield-x' : 'tabler-shield-check'"
                              size="sm" :icon-property="['width' => 16, 'height' => 16]"
                              :theme="$this->user->status->isActive() ? 'danger' : 'success'" icon-only
                              x-on:click="$ask.livewire('toggle-user-status', {
                                  id: null,
                                  textMessage: '{{  __('domains/identity.pages.user_detail.confirmation.toggle_status') }}',
                                  confirmText: '{{ __('ui.button.yes') }}',
                                  cancelText: '{{ __('ui.button.no') }}',
                              })" />
                </div>
            </x-slot:actions>
            <div class="row g-0">
                <div class="col-sm-12 col-md-4 px-2 py-1 fw-bold">
                    {{ __('domains/identity.fields.user.email') }}
                </div>
                <div class="col-sm-12 col-md-8 px-2 py-1">
                    {{ $this->user->email }}
                </div>

                <div class="col-sm-12 col-md-4 px-2 py-1 fw-bold">
                    {{ __('domains/identity.fields.role.name') }}
                </div>
                <div class="col-sm-12 col-md-8 px-2 py-1">
                    {{ $this->user->role_name }}
                </div>
            </div>
        </x-card>
    </div>
    <div class="col-md-6 col-sm-12">
        <x-card title="{{ __('domains/identity.pages.user_detail.user_info') }}">
            <div class="row g-0">
                <div class="col-sm-12 col-md-4 px-2 py-1 fw-bold">
                    {{ __('domains/identity.fields.user.name') }}
                </div>
                <div class="col-sm-12 col-md-8 px-2 py-1">
                    {{ $this->user->name }}
                </div>
                <div class="col-sm-12 col-md-4 px-2 py-1 fw-bold">
                    {{ __('domains/account.fields.profile.phone_number') }}
                </div>
                <div class="col-sm-12 col-md-8 px-2 py-1">
                    {{ $this->user->profile?->phone_number }}
                </div>
                <div class="col-sm-12 col-md-4 px-2 py-1 fw-bold">
                    {{ __('domains/account.fields.profile.gender') }}
                </div>
                <div class="col-sm-12 col-md-8 px-2 py-1">
                    {{ $this->user->profile?->gender?->label() }}
                </div>

                <div class="col-sm-12 col-md-4 px-2 py-1 fw-bold">
                    {{ __('domains/account.fields.profile.date_of_birth') }}
                </div>
                <div class="col-sm-12 col-md-8 px-2 py-1">
                    {{ $this->user->profile?->date_of_birth }}
                </div>
            </div>
        </x-card>
    </div>
</div>

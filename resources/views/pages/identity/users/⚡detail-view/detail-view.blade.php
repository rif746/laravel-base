@use(App\Domains\Identity\Enums\RoleType)
<section class="row">
    <div class="col-12 col-lg-6">
        <x-card title="{{ __('domains/identity/pages.user_detail.account_info') }}">
            <x-slot:actions>
                <div class="d-flex flex-row gap-1 align-items-center">
                    <x-badge :label="$this->user->status->label()" :variant="$this->user->status->badgeVariant()" />
                    @if(!$this->user->hasRole([RoleType::SYSTEM_ADMIN, RoleType::ADMIN]))
                        <x-menu.dropdown.container class="btn-sm" icon="tabler-dots-vertical" :icon-property="[
                                                        'width' => 16,
                                                        'height' => 16,
                                                    ]">
                            <x-menu.dropdown.item :label="__('domains/identity/pages.user_detail.menu.role_update')" icon="tabler-user-shield" :icon-property="['width' => 16, 'height' => 16]"
                                                  data-bs-target="#role-selection-modal" data-bs-toggle="modal" />
                            <x-menu.dropdown.item :label="__('domains/identity/pages.user_detail.menu.toggle_status')" :icon="$this->user->status->isActive() ? 'tabler-shield-x' : 'tabler-shield-check'" :icon-property="['width' => 16, 'height' => 16]"
                                                  x-on:click="$ask.livewire('toggle-user-status', {
                                                      id: null,
                                                      textMessage: '{{  __('domains/identity/pages.user_detail.confirmation.toggle_status') }}',
                                                      confirmText: '{{ __('ui/button.yes') }}',
                                                      cancelText: '{{ __('ui/button.no') }}',
                                                  })" />
                            <x-menu.dropdown.divider />
                            <x-menu.dropdown.item :label="__('domains/identity/pages.user_detail.menu.password_reset')" icon="tabler-user-password" :icon-property="['width' => 16, 'height' => 16]"
                                                  x-on:click="$ask.livewire('send-password-reset', {
                                                      id: null,
                                                      textMessage: '{{  __('domains/identity/pages.user_detail.confirmation.send_password_reset') }}',
                                                      confirmText: '{{ __('ui/button.yes') }}',
                                                      cancelText: '{{ __('ui/button.no') }}',
                                                  })" />
                        </x-menu.dropdown.container>
                    @endif
                </div>
            </x-slot:actions>
            <div class="row gy-3">
                <div class="col-12 col-md-4 font-bold">
                    {{ __('domains/identity/field.user.email') }}
                </div>
                <div class="col-12 col-md-8 text-muted">
                    {{ $this->user->email }}
                </div>

                <div class="col-12 col-md-4 font-bold">
                    {{ __('domains/identity/field.role.name') }}
                </div>
                <div class="col-12 col-md-8 text-muted">
                    {{ $this->user->role_name }}
                </div>
            </div>
        </x-card>
    </div>
    <div class="col-12 col-lg-6">
        <x-card title="{{ __('domains/identity/pages.user_detail.user_info') }}">
            <div class="row gy-3">
                <div class="col-12 col-md-4 font-bold">
                    {{ __('domains/identity/field.user.name') }}
                </div>
                <div class="col-12 col-md-8 text-muted">
                    {{ $this->user->name }}
                </div>
                <div class="col-12 col-md-4 font-bold">
                    {{ __('domains/account/field.profile.phone_number') }}
                </div>
                <div class="col-12 col-md-8 text-muted">
                    {{ $this->user->profile?->phone_number }}
                </div>
                <div class="col-12 col-md-4 font-bold">
                    {{ __('domains/account/field.profile.gender') }}
                </div>
                <div class="col-12 col-md-8 text-muted">
                    {{ $this->user->profile?->gender?->label() }}
                </div>

                <div class="col-12 col-md-4 font-bold">
                    {{ __('domains/account/field.profile.date_of_birth') }}
                </div>
                <div class="col-12 col-md-8 text-muted">
                    {{ $this->user->profile?->date_of_birth?->format('d/m/Y') }}
                </div>
            </div>
        </x-card>
    </div>

    <livewire:pages::identity.users.role-selection-modal :id="$id" />
</section>

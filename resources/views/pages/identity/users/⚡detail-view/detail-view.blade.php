@use(App\Domains\Identity\Enums\RoleType)

<div class="row g-3">
    <div class="col-sm-12">
        <x-card class="overflow-hidden p-0">
            <!-- Profile Header / Banner Area -->
            <div class="bg-body-tertiary border-bottom p-4">
                <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-3">
                    <!-- User Avatar -->
                    <div class="avatar position-relative flex-shrink-0" style="width: 80px; height: 80px">
                        @if ($this->user->avatar)
                            <img
                                src="{{ $this->user->avatar?->url }}"
                                alt="{{ $this->user->name }}"
                                class="rounded-circle object-fit-cover border-body h-100 w-100 border border-2 shadow-sm"
                            />
                        @else
                            <x-tabler-user-circle class="text-secondary bg-body rounded-circle border-body h-100 w-100 border border-2 p-1 shadow-sm" />
                        @endif
                    </div>

                    <!-- Header Primary Info -->
                    <div class="text-md-start flex-grow-1 text-center">
                        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
                            <div class="d-flex flex-column gap-2">
                                <h4 class="fw-bold text-body mb-0">{{ $this->user->name }}</h4>
                                <p class="text-body-secondary text-break fs-7 mb-0">{{ $this->user->email }}</p>
                                <span class="badge bg-primary-subtle text-primary border-primary-subtle rounded-pill fw-semibold border">
                                    {{ $this->user->role_name ?? '—' }}
                                </span>
                            </div>

                            <!-- Actions & Badges -->
                            <div class="d-flex align-items-center mt-md-0 mt-2 gap-2">
                                <x-badge
                                    :label="$this->user->status->label()"
                                    :variant="$this->user->status->variant()"
                                />

                                <div class="vr d-none d-sm-inline-block mx-1 opacity-25" style="height: 40px"></div>

                                @if (! $this->user->hasRole([RoleType::SYSTEM_ADMIN, RoleType::ADMIN]))
                                    <x-menu.dropdown.container
                                        class="btn-sm"
                                        icon="tabler-dots-vertical"
                                        :icon-property="['width' => 16, 'height' => 16]"
                                    >
                                        <x-menu.dropdown.item
                                            :label="__('domains/identity/buttons.user_detail.update_role')"
                                            icon="tabler-user-shield"
                                            :icon-property="['width' => 16, 'height' => 16]"
                                            data-bs-target="#role-selection-modal"
                                            data-bs-toggle="modal"
                                        />

                                        <x-menu.dropdown.item
                                            :label="__('domains/identity/buttons.user_detail.toggle_status')"
                                            :icon="$this->user->status->isActive() ? 'tabler-shield-x' : 'tabler-shield-check'"
                                            :icon-property="['width' => 16, 'height' => 16]"
                                            x-on:click="$ask.livewire('toggle-user-status', {
                                        id: null,
                                        textMessage: '{{ __('domains/identity/confirmations.user_detail.toggle_status') }}',
                                        confirmText: '{{ __('ui/button.yes') }}',
                                        cancelText: '{{ __('ui/button.no') }}',
                                    })"
                                        />

                                        <x-menu.dropdown.divider />

                                        <x-menu.dropdown.item
                                            :label="__('domains/identity/buttons.user_detail.send_password_reset')"
                                            icon="tabler-user-password"
                                            :icon-property="['width' => 16, 'height' => 16]"
                                            x-on:click="$ask.livewire('send-password-reset', {
                                        id: null,
                                        textMessage: '{{ __('domains/identity/confirmations.user_detail.send_password_reset') }}',
                                        confirmText: '{{ __('ui/button.yes') }}',
                                        cancelText: '{{ __('ui/button.no') }}',
                                    })"
                                        />
                                    </x-menu.dropdown.container>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details Inline Section -->
            <div class="p-4">
                <span class="text-body-secondary fw-semibold text-uppercase fs-7 d-block mb-3">
                    {{ __('domains/identity/sections.user_detail.user_info') }}
                </span>

                <!-- Fully Responsive Inline Items -->
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <!-- Phone Number -->
                    <div class="bg-body-tertiary d-flex align-items-center flex-grow-1 flex-wrap gap-2 rounded p-2 px-3">
                        <small class="text-body-secondary fw-semibold text-uppercase fs-7 text-nowrap">
                            {{ __('domains/account/field.profile.phone_number') }}:
                        </small>
                        <span class="fw-bold text-body text-break">{{ $this->user->profile?->phone_number ?? '—' }}</span>
                    </div>

                    <!-- Gender -->
                    <div class="bg-body-tertiary d-flex align-items-center flex-grow-1 flex-wrap gap-2 rounded p-2 px-3">
                        <small class="text-body-secondary fw-semibold text-uppercase fs-7 text-nowrap">
                            {{ __('domains/account/field.profile.gender') }}:
                        </small>
                        <span class="fw-bold text-body text-break">{{ $this->user->profile?->gender?->label() ?? '—' }}</span>
                    </div>

                    <!-- Date of Birth -->
                    <div class="bg-body-tertiary d-flex align-items-center flex-grow-1 flex-wrap gap-2 rounded p-2 px-3">
                        <small class="text-body-secondary fw-semibold text-uppercase fs-7 text-nowrap">
                            {{ __('domains/account/field.profile.date_of_birth') }}:
                        </small>
                        <span class="fw-bold text-body text-break">
                            {{ $this->user->profile?->date_of_birth?->format('d/m/Y') ?? '—' }}
                        </span>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
    <div class="col-sm-12 col-md-6">
        <livewire:widgets::identity.user-activities :user-id="$this->user->id" />
    </div>
    <livewire:pages::identity.users.role-selection-modal :id="$id" />
</div>

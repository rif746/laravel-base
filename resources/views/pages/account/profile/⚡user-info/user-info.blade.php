@use(App\Domains\Identity\Enums\RoleType)

<x-card class="overflow-hidden p-0">
    <!-- Profile Header / Banner Area -->
    <div class="bg-body-tertiary border-bottom p-4">
        <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-3">
            <!-- Interactive User Avatar -->
            <div
                class="avatar position-relative flex-shrink-0 cursor-pointer"
                style="width: 80px; height: 80px"
                data-bs-toggle="modal"
                data-bs-target="#update-avatar-modal"
                role="button"
            >
                @if ($this->user->avatar)
                    <img
                        src="{{ $this->user->avatar?->url }}"
                        alt="{{ $this->user->name }}"
                        class="rounded-circle object-fit-cover border-body h-100 w-100 border border-2 shadow-sm"
                    />
                @else
                    <x-tabler-user-circle class="text-secondary bg-body rounded-circle border-body h-100 w-100 border border-2 p-1 shadow-sm" />
                @endif

                <!-- Subtle Camera Overlay Badge -->
                <span class="position-absolute bg-primary rounded-circle d-flex align-items-center justify-content-center end-0 bottom-0 p-1 text-white shadow-sm">
                    <x-tabler-camera width="14" height="14" />
                </span>
            </div>

            <!-- Header Primary Info -->
            <div class="text-md-start flex-grow-1 text-center">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
                    <div class="d-flex flex-column gap-1">
                        <h4 class="fw-bold text-body mb-0">{{ $this->user->name }}</h4>
                        <p class="text-body-secondary text-break fs-7 mb-0">{{ $this->user->email }}</p>
                    </div>

                    <!-- Role & Status Badges -->
                    <div class="d-flex align-items-center mt-md-0 mt-2 flex-wrap gap-2">
                        <!-- Badges -->
                        <span class="badge bg-primary-subtle text-primary border-primary-subtle rounded-pill fw-semibold border">
                            {{ $this->user->role_name ?? '—' }}
                        </span>
                        <x-badge :label="$this->user->status->label()" :variant="$this->user->status->variant()" />

                        <!-- Subtle Vertical Divider (Hidden on xs, visible on sm+) -->
                        <div class="vr d-none d-sm-inline-block mx-1 opacity-25" style="height: 40px"></div>

                        <!-- Action Buttons -->
                        <div class="d-flex align-items-center ms-sm-0 ms-auto gap-1">
                            <x-button
                                icon="tabler-lock"
                                theme="warning"
                                size="sm"
                                data-id="{{ $this->user->id }}"
                                rounded
                                class="btn-icon"
                                data-bs-toggle="modal"
                                data-bs-target="#update-password-modal"
                            />
                            <x-button
                                icon="tabler-pencil"
                                theme="primary"
                                size="sm"
                                data-id="{{ $this->user->id }}"
                                rounded
                                class="btn-icon"
                                data-bs-toggle="modal"
                                data-bs-target="#update-profile-modal"
                            />
                        </div>
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

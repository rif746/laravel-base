<x-card :title="__('domains/account/pages.profile.title')" :subtitle="__('domains/account/pages.profile.description')">
    <x-slot:actions>
        <x-button icon="tabler-lock" theme="warning" size="sm" data-id="{{ $this->user->id }}" rounded class="btn-icon"
                  data-bs-toggle="modal" data-bs-target="#update-password-modal"/>
        <x-button icon="tabler-pencil" theme="primary" size="sm" data-id="{{ $this->user->id }}" rounded
                  class="btn-icon" data-bs-toggle="modal" data-bs-target="#update-profile-modal"/>
    </x-slot:actions>

    <div class="row">
        <div class="col-12 col-md-4 d-flex justify-content-center align-items-center mb-4 mb-md-0">
            <div class="avatar bg-light border cursor-pointer" style="width: 150px; height: 150px;" data-bs-toggle="modal" data-bs-target="#update-avatar-modal" role="button">
                @if($this->user->avatar)
                    <img src="{{ $this->user->avatar?->url }}" alt="" class="w-100 h-100 rounded-circle" style="object-fit: cover;">
                @else
                    <x-tabler-user-circle width="100%" height="100%" class="text-secondary rounded-circle"/>
                @endif
            </div>
        </div>
        <div class="col-12 col-md-8">
            <div class="row gy-3">
                <div class="col-12 col-md-4 font-bold">{{ __('domains/identity/field.user.name') }}</div>
                <div class="col-12 col-md-8 text-muted"><span>{{ $this->user->name }}</span></div>
                <div class="col-12 col-md-4 font-bold">{{ __('domains/identity/field.user.email') }}</div>
                <div class="col-12 col-md-8 text-muted d-flex align-items-center">
                    <span>{{ $this->user->email }}</span>
                    @if ($this->user->email_verified_at)
                        <span class="badge bg-success ms-2">{{ __('domains/identity/field.user.verified') }}</span>
                    @else
                        <span class="badge bg-danger ms-2 cursor-pointer"
                              wire:click="resendVerificationEmail()">{{ __('domains/identity/field.user.unverified') }}</span>
                    @endif
                </div>
                <div class="col-12 col-md-4 font-bold">{{ __('domains/account/field.profile.gender') }}</div>
                <div class="col-12 col-md-8 text-muted"><span>{{ $this->user->profile?->gender?->label() }}</span></div>
                <div class="col-12 col-md-4 font-bold">{{ __('domains/account/field.profile.date_of_birth') }}</div>
                <div class="col-12 col-md-8 text-muted">
                    <span>{{ $this->user->profile?->date_of_birth->format('d/m/Y') }}</span>
                </div>
                <div class="col-12 col-md-4 font-bold">{{ __('domains/account/field.profile.phone_number') }}</div>
                <div class="col-12 col-md-8 text-muted"><span>{{ $this->user->profile?->phone_number }}</span></div>
            </div>
        </div>
    </div>
</x-card>

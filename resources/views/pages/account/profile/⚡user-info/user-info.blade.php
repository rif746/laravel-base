<x-card :title="__('domains/account/pages.profile.title')" :subtitle="__('domains/account/pages.profile.description')">
    <x-slot:actions>
        <x-button icon="tabler-lock" theme="warning" size="sm" data-id="{{ $this->user->id }}" rounded class="btn-icon"
                  data-bs-toggle="modal" data-bs-target="#update-password-modal"/>
        <x-button icon="tabler-pencil" theme="primary" size="sm" data-id="{{ $this->user->id }}" rounded
                  class="btn-icon" data-bs-toggle="modal" data-bs-target="#update-profile-modal"/>
    </x-slot:actions>

    <div class="row">
        <div class="col-sm-12 col-md-4">
            <button class="avatar btn btn-icon border w-100 h-100" data-bs-toggle="modal" data-bs-target="#update-avatar-modal">
                @if($this->user->avatar)
                    <img src="{{ $this->user->avatar?->url }}" alt="" class="avatar">
                @else
                    <x-tabler-user-circle width="100%" height="100%" class="avatar rounded-circle"/>
                @endif
            </button>
        </div>
        <div class="col-sm-12 col-md-8">
            <div class="row">
                <div class="col-sm-12 col-md-4 fw-bold py-1">{{ __('domains/identity/field.user.name') }}</div>
                <div class="col-sm-12 col-md-8 py-1"><span>{{ $this->user->name }}</span></div>
                <div class="col-sm-12 col-md-4 fw-bold py-1">{{ __('domains/identity/field.user.email') }}</div>
                <div class="col-sm-12 col-md-8 py-1">
                    <span>{{ $this->user->email }}</span>
                    @if ($this->user->email_verified_at)
                        <span class="badge bg-success ms-1">{{ __('domains/identity/field.user.verified') }}</span>
                    @else
                        <span class="badge bg-danger ms-1 cursor-pointer"
                              wire:click="resendVerificationEmail()">{{ __('domains/identity/field.user.unverified') }}</span>
                    @endif
                </div>
                <div class="col-sm-12 col-md-4 fw-bold py-1">{{ __('domains/account/field.profile.gender') }}</div>
                <div class="col-sm-12 col-md-8 py-1"><span>{{ $this->user->profile?->gender?->label() }}</span></div>
                <div class="col-sm-12 col-md-4 fw-bold py-1">
                    {{ __('domains/account/field.profile.date_of_birth') }}</div>
                <div class="col-sm-12 col-md-8 py-1">
                    <span>{{ $this->user->profile?->date_of_birth->format('d/m/Y') }}</span>
                </div>
                <div class="col-sm-12 col-md-4 fw-bold py-1">{{ __('domains/account/field.profile.phone_number') }}
                </div>
                <div class="col-sm-12 col-md-8 py-1"><span>{{ $this->user->profile?->phone_number }}</span></div>
            </div>
        </div>
    </div>
</x-card>

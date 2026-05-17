<x-card :title="__('domains/account.pages.profile.title')" :subtitle="__('domains/account.pages.profile.description')">
    <x-slot:actions>
        <x-button icon="tabler-lock" theme="warning" size="sm" data-id="{{ $this->user->id }}" rounded class="btn-icon"
            data-bs-toggle="modal" data-bs-target="#update-password-modal" />
        <x-button icon="tabler-pencil" theme="primary" size="sm" data-id="{{ $this->user->id }}" rounded
            class="btn-icon" data-bs-toggle="modal" data-bs-target="#update-profile-modal" />
    </x-slot:actions>

    <div class="row">
        <div class="col-sm-12 col-md-4 fw-bold py-1">{{ __('domains/identity.fields.user.name') }}</div>
        <div class="col-sm-12 col-md-8 py-1"><span>{{ $this->user->name }}</span></div>
        <div class="col-sm-12 col-md-4 fw-bold py-1">{{ __('domains/identity.fields.user.email') }}</div>
        <div class="col-sm-12 col-md-8 py-1">
            <span>{{ $this->user->email }}</span>
            @if ($this->user->email_verified_at)
                <span class="badge bg-success ms-1">{{ __('domains/identity.fields.user.verified') }}</span>
            @else
                <span class="badge bg-danger ms-1 cursor-pointer"
                    wire:click="resendVerificationEmail()">{{ __('domains/identity.fields.user.unverified') }}</span>
            @endif
        </div>
        <div class="col-sm-12 col-md-4 fw-bold py-1">{{ __('domains/account.fields.profile.gender') }}</div>
        <div class="col-sm-12 col-md-8 py-1"><span>{{ $this->user->profile?->gender?->label() }}</span></div>
        <div class="col-sm-12 col-md-4 fw-bold py-1">
            {{ __('domains/account.fields.profile.date_of_birth') }}</div>
        <div class="col-sm-12 col-md-8 py-1"><span>{{ $this->user->profile?->date_of_birth->format('d/m/Y') }}</span>
        </div>
        <div class="col-sm-12 col-md-4 fw-bold py-1">{{ __('domains/account.fields.profile.phone_number') }}
        </div>
        <div class="col-sm-12 col-md-8 py-1"><span>{{ $this->user->profile?->phone_number }}</span></div>
    </div>
</x-card>

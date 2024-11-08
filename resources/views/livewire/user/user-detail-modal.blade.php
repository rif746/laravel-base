<div wire:modal>
    <x-modal wire:model="modal" :title="__($this->title)" persistent>
        <x-hr target="save,load" />

        <div class="row">
            <div class="sm:col-12 md:col-4 font-bold px-2 py-1">
                <img src="{{ tempFiles($this->users->photo_profile ?? 'profile.png') }}" class="w-20 rounded-lg" />
            </div>
            <div class="sm:col-12 md:col-8 px-2 py-1 pb-3 markdown">@markdown($this->users?->profile?->bio)</div>
            <div class="sm:col-12 md:col-4 px-2 py-1 font-bold">{{ __('locale/user.field.name') }}</div>
            <div class="sm:col-12 md:col-8 px-2 py-1">{{ $this->users->name ?? '-' }}</div>
            <div class="sm:col-12 md:col-4 px-2 py-1 font-bold">{{ __('locale/user.field.email') }}</div>
            <div class="sm:col-12 md:col-8 px-2 py-1">{{ $this->users->email ?? '-' }}</div>
            <div class="sm:col-12 md:col-4 px-2 py-1 font-bold">{{ __('locale/user.field.gender') }}</div>
            <div class="sm:col-12 md:col-8 px-2 py-1">
                {{ \App\Enum\GenderType::tryFrom($this->users?->profile?->gender)?->label() ?? '-' }}</div>
            <div class="sm:col-12 md:col-4 px-2 py-1 font-bold">{{ __('locale/user.field.village') }}</div>
            <div class="sm:col-12 md:col-8 px-2 py-1">{{ $this->users?->profile?->village ?? '-' }}</div>
            <div class="sm:col-12 md:col-4 px-2 py-1 font-bold">{{ __('locale/user.field.district') }}</div>
            <div class="sm:col-12 md:col-8 px-2 py-1">{{ $this->users?->profile?->district ?? '-' }}</div>
            <div class="sm:col-12 md:col-4 px-2 py-1 font-bold">{{ __('locale/user.field.city') }}</div>
            <div class="sm:col-12 md:col-8 px-2 py-1">{{ $this->users?->profile?->city ?? '-' }}</div>
            <div class="sm:col-12 md:col-4 px-2 py-1 font-bold">{{ __('locale/user.field.province') }}</div>
            <div class="sm:col-12 md:col-8 px-2 py-1">{{ $this->users?->profile?->province ?? '-' }}</div>
            <div class="sm:col-12 md:col-4 px-2 py-1 font-bold">Status</div>
            <div class="sm:col-12 md:col-8 px-2 py-1">{{ $this->users?->status ? 'Aktif' : 'Nonaktif' }}</div>
        </div>

        <x-slot:actions>
            <x-button label="Cancel" wire:click="$toggle('modal')" />
            @can('update', $this->users)
                <x-button class="{{ $this->users?->status ? 'btn-error' : 'btn-accent' }}" :label="trans_choice('locale/user.button.toggle_status', $this->users?->status)"
                    wire:click="toggleStatus" wire:target="toggleStatus" spinner />
            @endcan
        </x-slot:actions>
    </x-modal>
</div>

<x-modal id="user-view-modal" :title="$this->title" wire:submit="save" wire:loading form livewire>
    <div class="row mx-4">
        <div class="col-sm-12 col-md-4 fw-bold border-bottom px-4 py-2">{{ __('domains/identity.fields.user.name') }}
        </div>
        <div class="col-sm-12 col-md-8 border-bottom px-4 py-2">{{ $this->user->name }}</div>

        <div class="col-sm-12 col-md-4 fw-bold border-bottom px-4 py-2">{{ __('domains/identity.fields.user.email') }}
        </div>
        <div class="col-sm-12 col-md-8 border-bottom px-4 py-2">{{ $this->user->email }}</div>

        <div class="col-sm-12 col-md-4 fw-bold border-bottom px-4 py-2">{{ __('domains/identity.fields.role.name') }}
        </div>
        <div class="col-sm-12 col-md-8 border-bottom px-4 py-2">{{ $this->user->role_name }}</div>
    </div>
    <x-slot:footer>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('ui.button.close') }}</button>
    </x-slot:footer>
</x-modal>

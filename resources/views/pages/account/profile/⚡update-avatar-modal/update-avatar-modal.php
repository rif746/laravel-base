<?php

use App\Domains\Account\Actions\Profile\UpdateUserAvatar;
use App\Domains\Identity\Queries\GetAuthenticatedUserContext;
use App\Livewire\Concerns\WithModal;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

new class extends Component
{
    use WithFilePond;
    use WithModal;

    #[Validate(['required', 'file', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1024'])]
    public ?object $file = null;

    public $resourceName = 'avatar';

    public $mode = 'update';

    public function save(UpdateUserAvatar $updateUserAvatar): void
    {
        $this->validate();
        $updateUserAvatar->execute(auth('web')->user(), $this->file);
        $this->dispatch('hide-update-avatar-modal');
        $this->dispatch('profile-updated');
    }

    public function hide(): void
    {
        $this->resetValidation();
        $this->dispatch('filepond-reset-file');
    }
};

<?php

use App\Concerns\Livewire\Shared\WithModal;
use App\Domains\Account\Actions\Profile\UpdateUserAvatar;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

new class extends Component
{
    use WithModal;
    use WithFilePond;

    #[Validate(['required', 'file', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1024'])]
    public ?object $file = null;

    public $resourceName = 'avatar';
    public $mode = 'update';

    public function save(UpdateUserAvatar $updateUserAvatar): void
    {
        $this->validate();
        $updateUserAvatar->execute(auth('web')->user(), $this->file);
    }

    public function hide(): void
    {
        // TODO: Implement hide() method.
    }
};

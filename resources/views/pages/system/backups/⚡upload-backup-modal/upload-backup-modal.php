<?php

use App\Domains\System\Actions\Backup\UploadBackupFile;
use App\Livewire\Concerns\WithModal;
use App\Livewire\Concerns\WithToast;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\LivewireFilepond\WithFilePond;

new class extends Component
{
    use WithFilePond;
    use WithToast;
    use WithModal;

    #[Validate(['file', 'extensions:zip'])]
    public ?TemporaryUploadedFile $file = null;

    public function save(UploadBackupFile $uploadBackupFile): void
    {
        $this->validate();
        $uploadBackupFile->execute($this->file);
        $this->success(__('ui.crud.success.uploaded', ['resource' => __('resources.backup_file')]));
        $this->dispatch('hide-backup-file-upload-modal');
        $this->dispatch('reload-backup-data');
    }

    public function hide(): void
    {
        $this->resetErrorBag();
        $this->dispatch('filepond-reset-file');
    }
};

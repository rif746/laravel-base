<?php

use App\Domains\System\Actions\Backup\DeleteBackup;
use App\Domains\System\Actions\Backup\SystemBackup;
use App\Domains\System\Actions\Backup\SystemRestore;
use App\Domains\System\Actions\Backup\UploadBackupFile;
use App\Attributes\Seo;
use App\Concerns\Livewire\Seo\HasSeoAttributes;
use App\Concerns\Livewire\Shared\WithToast;
use App\Domains\System\DTOs\DeleteBackupDTO;
use App\Domains\System\Models\Backup;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\LivewireFilepond\WithFilePond;
use Symfony\Component\HttpFoundation\StreamedResponse;

new #[Layout('components.layouts.app')]
#[Seo(title: 'domains/system.seo.backup.title', description: 'domains/system.seo.backup.description', keywords: 'domains/system.seo.backup.keywords')]
class extends Component
{
    use HasSeoAttributes;
    use WithFilePond;
    use WithToast;

    #[Validate(['file', 'extensions:zip'])]
    public ?TemporaryUploadedFile $file = null;

    #[Computed]
    public function backupsData(): Collection|array
    {
        return Backup::all();
    }

    public function backup(SystemBackup $backup): void
    {
        try {
            $backup->execute();
            $this->success(__('domains/system.backups.backup_success'));
        } catch (Exception $exception) {
            logger($exception->getMessage());
            $this->error(__('domains/system.backups.backup_error'));
        }
    }

    public function download(Backup $backup): StreamedResponse
    {
        $file = Storage::disk($backup->disk)->exists($backup->path);
        if (! $file) {
            $this->error(__('domains/system.backups.download_error', ['path' => $backup->path]));
        }

        return Storage::disk($backup->disk)->download($backup->path);
    }

    public function restore(Backup $backup, SystemRestore $restore): void
    {
        try {
            $restore->execute($backup);
            $this->success(__('domains/system.backups.restored_success'));
        } catch (Exception $exception) {
            logger($exception->getMessage());
            $this->error(__('domains/system.backups.restored_error'));
        }
    }

    public function uploadFile(UploadBackupFile $uploadBackupFile): void
    {
        $this->validate();
        $uploadBackupFile->execute($this->file);
        $this->js("$('#backup-file-upload-modal').modal('hide')");
        $this->success(__('ui.crud.success.uploaded', ['resource' => __('resources.backup_file')]));
        $this->dispatch('hide-backup-file-upload-modal');
    }

    #[On('delete-data')]
    public function delete(string $id, DeleteBackup $action): void
    {
        $action->execute(new DeleteBackupDTO(id: $id));
        $this->dispatch('delete-data-completed');
    }
};

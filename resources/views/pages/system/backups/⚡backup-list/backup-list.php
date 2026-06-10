<?php

use App\Attributes\Seo;
use App\Domains\System\Actions\Backup\DeleteBackup;
use App\Domains\System\Actions\Backup\SystemBackup;
use App\Domains\System\Actions\Backup\SystemRestore;
use App\Domains\System\Models\Backup;
use App\Livewire\Concerns\HasSeoAttributes;
use App\Livewire\Concerns\WithToast;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

new #[Layout('components.layouts.app')]
#[Seo(title: 'domains/system.seo.backup.title', description: 'domains/system.seo.backup.description', keywords: 'domains/system.seo.backup.keywords')]
class extends Component
{
    use HasSeoAttributes;
    use WithToast;

    #[On('reload-backup-data')]
    #[Computed]
    public function backupsData(): Collection|array
    {
        return Backup::all();
    }

    public function backup(SystemBackup $backup): void
    {
        try {
            $backup->execute();
            $this->success(__('domains/system.messages.backup.backup_success'));
        } catch (Exception $exception) {
            logger($exception->getMessage());
            $this->error(__('domains/system.messages.backup.backup_error'));
        }
    }

    public function download(Backup $backup): StreamedResponse
    {
        $file = Storage::disk($backup->disk)->exists($backup->path);
        if (! $file) {
            $this->error(__('domains/system.messages.backup.download_error', ['path' => $backup->path]));
        }

        return Storage::disk($backup->disk)->download($backup->path);
    }

    public function restore(Backup $backup, SystemRestore $restore): void
    {
        try {
            $restore->execute($backup);
            $this->success(__('domains/system.messages.backup.restored_success'));
        } catch (Exception $exception) {
            logger($exception->getMessage());
            $this->error(__('domains/system.messages.backup.restored_error'));
        }
    }

    #[On('delete-data')]
    public function delete(Backup $id, DeleteBackup $action): void
    {
        $action->execute($id);
        $this->dispatch('delete-data-completed');
    }
};

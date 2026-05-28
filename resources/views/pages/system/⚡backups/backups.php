<?php

use App\Actions\System\Backup\SystemBackup;
use App\Actions\System\Backup\SystemRestore;
use App\Attributes\Seo;
use App\Concerns\Livewire\Seo\HasSeoAttributes;
use App\Models\System\Backup;
use Illuminate\Support\Collection;
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

    #[Computed]
    public function backupsData(): Collection|array
    {
        return Backup::all();
    }

    public function backup(SystemBackup $backup): void
    {
        try {
            $backup->execute();
            $this->js("window.toast('System Backup successfully', 'success')");
        } catch (Exception $exception) {
            logger($exception->getMessage());
            $this->js("window.toast('Failed to Backup System', 'error')");
        }
    }

    public function download(Backup $backup): StreamedResponse
    {
        $file = Storage::disk($backup->disk)->exists($backup->path);
        if (!$file) {
            $this->js("window.toast('Failed to download $backup->path', 'error');)");
        }

        return Storage::disk($backup->disk)->download($backup->path);
    }

    public function restore(Backup $backup, SystemRestore $restore): void
    {
        try {
            $restore->execute($backup);
            $this->js("window.toast('System Restore successfully', 'success')");
        } catch (Exception $exception) {
            logger($exception->getMessage());
            $this->js("window.toast('Failed to Restore System', 'error')");
        }
    }

    #[On('delete-data')]
    public function delete(string $id): void
    {
        $backup = Backup::find($id);
        remove_file($backup->path);
        $backup->delete();
        $this->dispatch('delete-data-completed');
    }
};

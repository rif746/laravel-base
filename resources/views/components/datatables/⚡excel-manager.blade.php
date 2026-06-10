<?php

use App\Domains\System\Jobs\NotifyExportReady;
use App\Exports\StyledExport;
use App\Livewire\Concerns\WithModal;
use App\Livewire\Concerns\WithToast;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\LivewireFilepond\WithFilePond;

new class extends Component {
    use WithToast;
    use WithModal;
    use WithFilePond;

    #[Locked]
    public string $importClass;

    #[Locked]
    public string $exportClass;

    #[Locked]
    public string $resourceName;

    #[Validate]
    public ?TemporaryUploadedFile $file = null;

    protected string $mode = 'upload';

    protected function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        ];
    }

    public function import(): void
    {
        if (!$this->importClass) return;

        $filePath = $this->file->store('excel/import/' . $this->resourceName, ['disk' => 'local']);
        $initiatorId = auth('web')->id();
        $importId = Str::uuid()->toString();

        $importInstance = new $this->importClass($importId, $initiatorId);
        Excel::queueImport($importInstance, $filePath);
        $this->success(__('ui.excel.import.success'));
    }

    #[On('export-excel')]
    public function export(): void
    {
        if (!$this->exportClass) return;
        $recipentEmail = auth('web')->user()->email;
        $storagePath = 'excel/export/' . $this->resourceName . '_' . time() . '.xlsx';
        $exportInstance = new $this->exportClass();
        $styledExport = new StyledExport($exportInstance);

        \Maatwebsite\Excel\Facades\Excel::queue($styledExport, $storagePath)->chain([
            new NotifyExportReady(
                recipientEmail: $recipentEmail,
                filePath: $storagePath,
                downloadName: 'Report_' . $this->resourceName,
            )
        ]);

        $this->success(__('ui.excel.export.success'));
    }

    public function hide(): void
    {
        $this->reset('file');
        $this->resetValidation();
        $this->resetErrorBag();
    }
};
?>

<div>
    @if($importClass)
        <x-modal id="excel-import-modal" size="modal-lg" :title="$this->title" form wire:submit="import" livewire>
            <x-filepond::upload :label="__('ui.excel.import.file_label')" wire:model="file"/>
            <x-slot:footer>
                <x-button theme="primary" type="submit" :label="__('ui.button.upload')"/>
                <x-button theme="secondary" data-bs-dismiss="modal" :label="__('ui.button.cancel')"/>
            </x-slot:footer>
            @push('scripts')
                @filepondScripts
            @endpush
        </x-modal>
    @endif
</div>

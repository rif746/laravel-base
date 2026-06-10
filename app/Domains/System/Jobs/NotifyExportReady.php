<?php

namespace App\Domains\System\Jobs;

use App\Domains\System\Events\ExportCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Maatwebsite\Excel\Facades\Excel;

class NotifyExportReady implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $recipientEmail,
        private readonly string $filePath,
        private readonly string $downloadName,
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ExportCompleted::dispatch(
            $this->recipientEmail,
            $this->filePath,
            $this->downloadName,
        );
    }
}

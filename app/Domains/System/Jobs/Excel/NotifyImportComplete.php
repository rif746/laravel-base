<?php

namespace App\Domains\System\Jobs\Excel;

use App\Domains\System\Events\ImportCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotifyImportComplete implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly string $recipientEmail)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ImportCompleted::dispatch($this->recipientEmail);
    }
}

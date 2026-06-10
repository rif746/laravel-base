<?php

namespace App\Domains\System\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExportCompleted implements ShouldQueue
{
    use Queueable, Dispatchable, SerializesModels;

    public function __construct(
        public readonly string $recipientEmail,
        public readonly string $filePath,
        public readonly string $downloadName
    ) {}
}

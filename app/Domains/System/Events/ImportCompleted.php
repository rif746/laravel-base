<?php

namespace App\Domains\System\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportCompleted
{

    use Dispatchable, SerializesModels;
    public function __construct(
        public readonly string $recipientEmail,
        public readonly string $filePath,
    ) {}
}

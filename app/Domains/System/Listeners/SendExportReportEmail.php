<?php

namespace App\Domains\System\Listeners;

use App\Mail\ExcelExportEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendExportReportEmail implements ShouldQueue
{
    public function handle(object $event): void
    {
        Mail::to($event->recipientEmail)->send(
            new ExcelExportEmail($event->filePath, $event->downloadName)
        );
    }
}

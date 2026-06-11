<?php

namespace App\Domains\System\Listeners\Excel;

use App\Domains\System\Mail\Excel\ExcelExportEmail;
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

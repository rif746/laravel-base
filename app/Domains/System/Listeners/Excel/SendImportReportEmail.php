<?php

namespace App\Domains\System\Listeners\Excel;

use App\Domains\System\Mail\Excel\ExcelExportEmail;
use App\Domains\System\Mail\Excel\ExcelImportEmail;
use Illuminate\Support\Facades\Mail;

class SendImportReportEmail
{
    public function handle(object $event): void
    {
        Mail::to($event->recipientEmail)->send(
            new ExcelImportEmail()
        );
    }
}

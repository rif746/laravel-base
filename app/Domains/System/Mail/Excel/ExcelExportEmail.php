<?php

namespace App\Domains\System\Mail\Excel;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class ExcelExportEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(private readonly string $filePath, private readonly string $downloadName)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('domains/system/notifications.excel.export_email.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $message = (new MailMessage)
            ->subject(__('domains/system/notifications.excel.export_email.subject'))
            ->line(__('domains/system/notifications.excel.export_email.intro'))
            ->line(__('domains/system/notifications.excel.export_email.body'))
            ->line(__('domains/system/notifications.excel.export_email.outro'))
            ->render();

        return new Content(
            htmlString: $message,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk('local', $this->filePath)->as($this->downloadName),
        ];
    }
}

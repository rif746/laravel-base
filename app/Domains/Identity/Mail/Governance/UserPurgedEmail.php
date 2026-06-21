<?php

namespace App\Domains\Identity\Mail\Governance;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class UserPurgedEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('domains/identity/notifications.governance.user_purged.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $message = (new MailMessage)
            ->line(__('domains/identity/notifications.governance.user_purged.intro'))
            ->line(__('domains/identity/notifications.governance.user_purged.body'))
            ->line(__('domains/identity/notifications.governance.user_purged.outro'))
            ->render();
        return new Content(
            htmlString: $message,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

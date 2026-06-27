<?php

namespace App\Domains\Identity\Mail\Governance;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class UserSuspendedEmail extends Mailable implements ShouldQueue
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
            subject: __('domains/identity/notifications.governance.user_suspended.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $message = (new MailMessage)
            ->line(__('domains/identity/notifications.governance.user_suspended.intro'))
            ->line(__('domains/identity/notifications.governance.user_suspended.body'))
            ->line(__('domains/identity/notifications.governance.user_suspended.outro'))
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
        return [];
    }
}

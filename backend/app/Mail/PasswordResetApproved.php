<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Notifies a staff member that an administrator approved their password
 * reset. The new password travels with this email because the legacy
 * flow has no self-service password form — the administrator applies it
 * and the staff member signs in with it.
 */
class PasswordResetApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $recipientName,
        public string $newPassword,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Hospital System password was reset',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.password-reset-approved',
        );
    }
}

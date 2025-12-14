<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $token;
    public $resetUrl;

    public function __construct($admin, $token)
    {
        $this->admin = $admin;
        $this->token = $token;
        $this->resetUrl = route('admin.password.reset', $token);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[BBSPGL] Reset Password Administrator',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-password-reset',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

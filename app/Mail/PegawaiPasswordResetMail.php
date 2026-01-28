<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PegawaiPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pegawai;
    public $token;
    public $resetUrl;

    public function __construct($pegawai, $token)
    {
        $this->pegawai = $pegawai;
        $this->token = $token;
        $this->resetUrl = route('pegawai.password.reset', $token);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[BBSPGL] Reset Password Pegawai ESDM',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pegawai-password-reset',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

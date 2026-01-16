<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\PegawaiInternal;

class PegawaiVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pegawai;
    public $token;
    public $verificationUrl;

    public function __construct(PegawaiInternal $pegawai, string $token)
    {
        $this->pegawai = $pegawai;
        $this->token = $token;
        $this->verificationUrl = route('pegawai.verify', $token);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[BBSPGL] Verifikasi Email Pegawai Internal',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pegawai-verification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

<?php

namespace App\Mail;

use App\Models\PartnerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartnerApplicationAcknowledgement extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PartnerApplication $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your partnership request – EUROPA FUSION',
            replyTo: [new Address(config('site.email'))],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.partner-application-acknowledgement');
    }
}

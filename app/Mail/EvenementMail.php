<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EvenementMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<int, string>  $lignes
     */
    public function __construct(
        public string $sujet,
        public string $titre,
        public string $intro,
        public array $lignes = [],
        public ?string $actionUrl = null,
        public ?string $actionText = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->sujet);
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.evenement');
    }
}

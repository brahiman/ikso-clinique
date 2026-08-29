<?php

namespace App\Mail;

use App\Models\DemandeConsultation;
use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConsultationDistanceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public DemandeConsultation $demande,
        public RendezVous $rendezVous,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre consultation à distance — Clinique IKSO',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.consultation-distance',
            with: [
                'demande' => $this->demande,
                'rendezVous' => $this->rendezVous,
                'notes' => $this->rendezVous->notes,
            ],
        );
    }
}

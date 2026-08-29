<?php

namespace App\Mail;

use App\Models\DemandeExamen;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DemandeExamenMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public DemandeExamen $demande)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Votre demande d'examens — Clinique IKSô",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.demande-examen',
            with: ['demande' => $this->demande],
        );
    }

    public function attachments(): array
    {
        $this->demande->loadMissing(['patient', 'consultation.medecin.user', 'consultation.medecin.specialites', 'typesExamens']);

        $pdf = Pdf::loadView('pdf.demande-examen', ['demande' => $this->demande])->setPaper('a4');

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                'demande-examen-'.$this->demande->id.'.pdf'
            )->withMime('application/pdf'),
        ];
    }
}

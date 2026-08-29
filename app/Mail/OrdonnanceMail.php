<?php

namespace App\Mail;

use App\Models\Ordonnance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrdonnanceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Ordonnance $ordonnance)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre ordonnance — Clinique IKSO',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ordonnance',
            with: [
                'ordonnance' => $this->ordonnance,
            ],
        );
    }

    public function attachments(): array
    {
        $this->ordonnance->loadMissing([
            'patient',
            'medecin.user',
            'medecin.specialites',
            'details.medicament',
        ]);

        $pdf = Pdf::loadView(
            'pdf.ordonnance',
            ['ordonnance' => $this->ordonnance]
        )
        ->setPaper('a4');

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                'ordonnance-'.$this->ordonnance->id.'.pdf'
            )->withMime('application/pdf'),
        ];
    }
}


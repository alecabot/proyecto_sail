<?php

namespace App\Mail;

use DOMDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CorreoPuntosParte extends Mailable
{
    use Queueable, SerializesModels;

    protected $alumno;



    /**
     * Create a new message instance.
     */
    public function __construct($alumno)
    {
        $this->alumno = $alumno;

    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('sergio_gb02@hotmail.com', 'Sergio'),
            replyTo: [
                new Address('sergioggbb02@gmail.com', 'Sergio 2'),
            ],

            subject: 'Penalizacion Puntos',


        );
    }

    /**
     * Get the message content definition.
     */
    public function build(): CorreoPuntosParte
    {


        return $this->view('parte.puntos')
            ->with([
                'alumno' => $this->alumno,
            ]);

    }


    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

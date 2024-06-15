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

class CorreoTutores extends Mailable
{
    use Queueable, SerializesModels;

    protected $alumno;
    protected $parte;
    protected bool $eliminado;

    protected bool $actualizado;

    /**
     * Create a new message instance.
     */
    public function __construct($alumno, $parte, $eliminado = false, $actualizado = false)
    {
        $this->alumno = $alumno;
        $this->parte = $parte;
        $this->eliminado = $eliminado;
        $this->actualizado = $actualizado;
    }

    public function envelope(): Envelope
    {
        if ($this->eliminado) {
            $this->subject = 'Parte de incidencias eliminado';
        } else if ($this->actualizado) {
            $this->subject = 'Parte de incidencias actualizado';
        } else {
            $this->subject = 'Parte de incidencias';
        }
        return new Envelope(
            from: new Address('sergio_gb02@hotmail.com', 'Sergio'),
            replyTo: [
                new Address('sergioggbb02@gmail.com', 'Sergio 2'),
            ],

            subject: 'Parte de incidencias',


        );
    }

    /**
     * Get the message content definition.
     */
    public function build(): CorreoTutores
    {
        if (!empty($parte->descripcion_detallada)) {

            $dom = new \DOMDocument();
            @$dom->loadHTML($this->parte->descripcion_detallada, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

            $images = $dom->getElementsByTagName('img');
            $this->imagePaths = [];

            foreach ($images as $image) {
                $src = $image->getAttribute('src');

                if (!filter_var($src, FILTER_VALIDATE_URL)) {
                    $absolutePath = public_path($src);

                    if (file_exists($absolutePath)) {
                        $this->imagePaths[] = $absolutePath;
                        // Replace the image src with the CID
                        $image->setAttribute('src', "cid:" . basename($absolutePath));
                        // Remove the image element
                        $image->parentNode->removeChild($image);
                    }
                }
            }

            $this->parte->descripcion_detallada = $dom->saveHTML();
        }

        return $this->view('parte.correotutores')
            ->with([
                'parte' => $this->parte,
                'alumno' => $this->alumno,
                'imagePaths' => $this->imagePaths ?? null,
                'actualizado' => $this->actualizado,
                'eliminado' => $this->eliminado,
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

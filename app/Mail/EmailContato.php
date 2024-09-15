<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Facades\Empresa;

class EmailContato extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    // O método build prepara a estrutura do e-mail
    public function build()
    {
        // Verifica se o SMTP dinâmico da empresa está configurado
        $from = config('mail.from.address');

        return $this->from($from)  // Email autorizado do domínio do remetente
                    ->replyTo($this->details['emailCliente'])   // Definir o email de resposta
                    ->subject($this->details['titulo'])
                    ->view('email.emailContato')
                    ->with('details', $this->details);
    }

    /**
     * Get the message envelope.
     */
    /*
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Contato',
        );
    }*/

    /**
     * Get the message content definition.
     *//*
    public function content(): Content
    {
        return new Content(
            view: 'email.emailContato',
        );
    }*/

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     *//*
    public function attachments(): array
    {
        return [];
    }*/
}

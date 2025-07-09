<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendDocuments extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Devi Eye Hospitals - Documents',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'email.send-documents',
            with: $this->data
        );
    }

    public function build()
    {
        return $this->subject('Devi Eye Hospitals - Documents')
            ->view('email.send-documents');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        $docs = array();
        if ($this->data['is_mrecord'])
            array_push($docs, Attachment::fromData(fn() => $this->data['mrecord']->output(), 'invoice.pdf')->withMime('application/pdf'));
        if ($this->data['is_phistory'])
            array_push($docs, Attachment::fromData(fn() => $this->data['phistory']->output(), 'receipt.pdf')->withMime('application/pdf'));
        if ($this->data['is_spectacle'])
            array_push($docs, Attachment::fromData(fn() => $this->data['spectacle']->output(), 'prescription.pdf')->withMime('application/pdf'));
        return $docs;
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FinancialReportEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $attachmentPath;
    public $content;
    public $fileName;
    /**
     * Create a new message instance.
     */
    public function __construct($attachmentPath, $filename)
    {
        $this->attachmentPath = $attachmentPath;
        $this->fileName = $filename;
        $this->content = "This is the approved $filename report.";
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->fileName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.financialreport',
        ); 
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->attachmentPath)
        ];
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FailedPaymentEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $transactionId;
    public $planName;
    public $lastFailedPayment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $transactionId,$planName,$lastFailedPayment)
    {
        $this->data = $data;
        $this->transactionId = $transactionId;
        $this->planName = $planName;
        $this->lastFailedPayment = $lastFailedPayment;

    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Tu pago fue rechazado',
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
            view: 'emails.subscriptions.failedPayment',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}

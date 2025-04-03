<?php

namespace App\Mail;

use App\Models\Administration\Plan;
use App\Models\Administration\Transaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $plan;
    public $transaction;
    public $transactionAmount;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Plan $plan, Transaction $transaction)
    {
        $this->user = $user;
        $this->plan = $plan;
        $this->transaction = $transaction;
        $this->transactionAmount = null;

        if($transaction->type == "bank_transfer")
        {
            $extra = json_decode($transaction->data, true);
            $this->transactionAmount = round(($extra["extra"]["price"] * $plan->price), 2);
        }


    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Pestsy - Recibo de pago',
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
            view: 'emails.invoice.index',
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

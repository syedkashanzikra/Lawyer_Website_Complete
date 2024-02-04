<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserPaymentReminder extends Mailable
{
    use Queueable, SerializesModels;
    public $bill;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bill,$user)
    {
        //
         $this->bill = $bill;
         $this->user = $user;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function build()
    {
        return $this->subject('Payment Reminder')
            ->view('email.payment_reminder')
            ->with([
                'bill' => $this->bill,
                'user' => $this->user,
            ]);
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

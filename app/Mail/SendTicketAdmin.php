<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTicketAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $ticket;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$ticket)
    {
        $this->ticket = $ticket;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.create_ticket_admin')->subject('New Ticket Opened ['.$this->ticket->ticket_id.']');
    }
}

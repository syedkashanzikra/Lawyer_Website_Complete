<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTicketReply extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $ticket;
    public $conversion;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$ticket,$conversion)
    {
        $this->ticket = $ticket;
        $this->user = $user;
        $this->conversion = $conversion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.create_ticket_reply')->subject('New reply on ticket ['.$this->ticket->ticket_id.']');
    }
}

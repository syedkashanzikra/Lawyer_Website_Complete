<?php

namespace App\Models\Mail;

use App\Models\Utility;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BillSend extends Mailable
{
    use Queueable, SerializesModels;

    public $bill;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bill)
    {
        $this->bill = $bill;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(\Auth::user()->type == 'super admin')
        {
            return $this->view('email.bill_send')->with('bill', $this->bill)->subject('Ragarding to product/service bill generator.');
        }
        else
        {
            return $this->from(Utility::getValByName('company_email'), Utility::getValByName('company_email_from_name'))->view('email.bill_send')->with('bill', $this->bill)->subject('Ragarding to product/service bill generator.');
        }


    }
}

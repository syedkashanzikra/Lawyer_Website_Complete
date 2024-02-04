<?php

namespace App\Models\Mail;

use App\Models\Utility;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreate extends Mailable
{
    use Queueable, SerializesModels;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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
            return $this->markdown('email.user_create')->subject('Login details - '.env('APP_NAME'))->with('user', $this->user);
        }
        else
        {
            return $this->from(Utility::getValByName('company_email'), Utility::getValByName('company_email_from_name'))->markdown('email.user_create')->subject('Login details - ' . (isset(Utility::settings()['company_name']) && !empty(Utility::settings()['company_name']))?Utility::settings()['company_name']:env('APP_NAME'))->with('user', $this->user);

        }


    }
}

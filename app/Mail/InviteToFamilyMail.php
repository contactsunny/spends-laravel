<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviteToFamilyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $viewData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($viewData)
    {
        $this->viewData = $viewData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.inviteToFamily')
                    ->subject('You are invited to Spends!')
                    ->replyTo('sunny.3mysore@gmail.com');
    }
}

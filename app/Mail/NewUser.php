<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUser extends Mailable
{
    use Queueable, SerializesModels;

    public $newUser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $newUser)
    {
        $this->newUser = $newUser;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from('sas.services@fivesgroup.com')
            ->subject($this->newUser['subject'])
            ->to($this->newUser['target'])
            ->cc($this->newUser['contacts'])
            ->view('frontend.mail.notify-new-user');
    }
}

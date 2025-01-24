<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $updRequest;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $updRequest)
    {
        $this->updRequest = $updRequest;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        dd($this->from('sas.services@fivesgroup.com')
//            ->to($this->newRequest['email'])
//            ->view('frontend.mail.new-request'));

        //on interroge iTop pour récupérer les destinataires
        // TO : Agent
        // CC : la liste des contacts


        return $this->from('sas.services@fivesgroup.com')
            ->subject($this->updRequest['subject'])
            ->to($this->updRequest['agent'])
            ->cc($this->updRequest['contacts'])
            ->view('frontend.mail.update-request');
    }
}

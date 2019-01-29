<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'zhegwood@gmail.com';
        $subject = 'Laravel/Vue/Bulma Activation';
        $name = 'Zach Hegwood';

        return $this->view('emails.activation')
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject)
            ->with(['link' => $this->data['link']]);
    }
}

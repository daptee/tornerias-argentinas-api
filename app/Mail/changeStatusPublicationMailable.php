<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class changeStatusPublicationMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $data, $subject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        $publication_title = $data['publication_title'];
        $status = $data['status'];
        $this->subject = "Tornerias Argentinas: $publication_title - $status"; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.changeStatusPublication');
    }
}

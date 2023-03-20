<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OfficialBusinessNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($data)
    {
        $this->request_data = $data;  
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('HRIS - Official Business Approval')
        ->view('email.ob_notification')
        ->with([
            'details'  =>  $this->request_data
        ]);
    }
}

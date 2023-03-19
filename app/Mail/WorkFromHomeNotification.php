<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WorkFromHomeNotification extends Mailable
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
        return $this->subject('HRIS - Work From Home Approval')
        ->view('email.wfh_notification')
        ->with([
            'details'  =>  $this->request_data
        ]);
    }
}

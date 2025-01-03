<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PayslipNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected $id,
        $cutoff_from,
        $cutoff_to;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id,$cutoff_from,$cutoff_to)
    {
        $this->id = $id;
        $this->cutoff_from = $cutoff_from;
        $this->cutoff_to = $cutoff_to;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('HRIS - PAYSLIP '.$this->cutoff_from. ' - '.$this->cutoff_to)
        ->view('payslips.email')
        ->with([
            'link' => url('/payslip-print/'.$this->id),
        ]);
    }
}

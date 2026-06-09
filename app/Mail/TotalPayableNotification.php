<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TotalPayableNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $totalPayable;

    /**
     * Create a new message instance.
     *
     * @param $client
     * @param $totalPayable
     */
    public function __construct($client, $totalPayable)
    {
        $this->client = $client;
        $this->totalPayable = $totalPayable;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Invoice | Asif Associates')
                    ->view('emails.total_payable_notification');
    }
}

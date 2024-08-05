<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProcurementDataEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $csvFilePath;


    public function __construct($csvFilePath)
    {
        $this->csvFilePath = $csvFilePath;
    }


    public function build()
    {
        return $this->subject('Procurement Data')
                    ->view('email.procurementDataEmail')
                    ->attach($this->csvFilePath, [
                        'as' => 'procurement.csv',
                        'mime' => 'text/csv',
                ]);
    }
}

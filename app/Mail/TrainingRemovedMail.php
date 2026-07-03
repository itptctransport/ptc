<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrainingRemovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $driver;
    public $training;

    public function __construct($driver, $training)
    {
        $this->driver = $driver;
        $this->training = $training;
    }

    public function build()
    {
        return $this->view('emails.training_removed')
            ->subject('Training Assignment Cancelled: ' . $this->training->trainingCourse->name)
            ->with([
                'driver'   => $this->driver,
                'training' => $this->training,
            ]);
    }
}

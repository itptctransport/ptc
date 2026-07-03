<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrainingRescheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $driver;
    public $training;
    public $fromDate;
    public $toDate;
    public $icsFilePath;

    public function __construct($driver, $training, $fromDate, $toDate, $icsFilePath = null)
    {
        $this->driver      = $driver;
        $this->training    = $training;
        $this->fromDate    = $fromDate;
        $this->toDate      = $toDate;
        $this->icsFilePath = $icsFilePath;
    }

    public function build()
    {
        $email = $this->view('emails.training_rescheduled')
            ->subject('Training Rescheduled: ' . $this->training->trainingCourse->name)
            ->with([
                'driver'   => $this->driver,
                'training' => $this->training,
                'fromDate' => $this->fromDate,
                'toDate'   => $this->toDate,
            ]);

        if ($this->icsFilePath) {
            $email->attach($this->icsFilePath, [
                'as'   => 'training_details.ics',
                'mime' => 'text/calendar',
            ]);
        }

        return $email;
    }
}

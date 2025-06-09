<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PendingAppointment;
use Carbon\Carbon;

class AppointmentDeclined extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $start;

    /**
     * Create a new message instance.
     *
     * @param  PendingAppointment  $appointment
     */
    public function __construct(PendingAppointment $appointment)
    {
        $this->appointment = $appointment;

        $locationTimezone = $appointment->location->timezone ?? config('app.timezone');
        $this->start = Carbon::parse($appointment->date . ' ' . $appointment->time, $locationTimezone);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Your Appointment Request Was Declined';

        return $this->subject($subject)
                    ->view('emails.appointment-declined')
                    ->with([
                        'appointment'    => $this->appointment,
                        'start'          => $this->start,
                        'clientName'     => $this->appointment->pet->client->first_name,
                        'petName'        => $this->appointment->pet->name,
                        'declineReason'  => $this->appointment->decline_reason,
                    ]);
    }
}

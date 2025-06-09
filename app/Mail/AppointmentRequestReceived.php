<?php

namespace App\Mail;

use App\Models\PendingAppointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentRequestReceived extends Mailable
{
    use Queueable, SerializesModels;

    public PendingAppointment $appointment;

    public function __construct(PendingAppointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function build()
    {
        return $this->subject('New Appointment Request Received')
                    ->markdown('emails.appointments.received')
                    ->with([
                        'appointment' => $this->appointment,
                        'location'    => $this->appointment->location,
                        'pet'         => $this->appointment->pet,
                        'client'      => $this->appointment->pet->client ?? null,
                        'service'     => $this->appointment->service,
                    ]);
    }
}

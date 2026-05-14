<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationPendingMail
    extends Mailable
{
    use Queueable,
        SerializesModels;

    public $reservation;

    public function __construct(
        Reservation $reservation
    )
    {
        $this->reservation =
            $reservation;
    }

    public function build()
    {
        return $this
            ->subject(
                'Recibimos tu solicitud de reserva ⏳'
            )
            ->view(
                'emails.reservation-pending'
            );
    }
}
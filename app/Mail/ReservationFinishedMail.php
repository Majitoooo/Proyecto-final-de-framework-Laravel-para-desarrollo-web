<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationFinishedMail
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
                'Tu reserva ha finalizado ⭐'
            )
            ->view(
                'emails.reservation-finished'
            );
    }
}
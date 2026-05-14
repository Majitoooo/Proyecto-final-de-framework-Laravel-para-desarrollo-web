<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;

class FinishReservations
    extends Command
{
    protected $signature =
        'reservations:finish';

    protected $description =
        'Finaliza reservas vencidas';

    public function handle()
    {
        $reservations =
            Reservation::where(
                'status',
                'confirmed'
            )
            ->where(
                'end_time',
                '<=',
                now()
            )
            ->get();

        foreach (
            $reservations
            as
            $reservation
        ) {

            $reservation->update([
                'status'
                    =>
                    'finished'
            ]);

            Mail::to(
                $reservation->user_email
            )->send(
                new \App\Mail\ReservationFinishedMail(
                    $reservation
                )
            );
            
        }

        $this->info(
            'Reservas actualizadas.'
        );
    }
}
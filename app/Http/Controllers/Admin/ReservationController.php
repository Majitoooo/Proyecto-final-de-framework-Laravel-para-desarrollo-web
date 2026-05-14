<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Space;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmedMail;
use App\Mail\ReservationRejectedMail;
use App\Mail\ReservationCancelledMail;
use App\Mail\ReservationFinishedMail;

class ReservationController extends Controller
{

    public function index(
        Request $request
    )
    {
        $query =
            Reservation::with(
                'space'
            );

        // Filtro estado

        if (
            $request->filled(
                'status'
            )
        ) {

            $query->where(
                'status',
                $request->status
            );
        }

        // Filtro espacio

        if (
            $request->filled(
                'space_id'
            )
        ) {

            $query->where(
                'space_id',
                $request->space_id
            );
        }

        // Filtro fecha

        if (
            $request->filled(
                'date'
            )
        ) {

            $query->whereDate(
                'start_time',
                $request->date
            );
        }

        $reservations =
            $query
                ->latest()
                ->get();

        return Inertia::render(
            'Admin/Reservations/Index',
            [

                'reservations'
                    => $reservations,

                'filters'
                    => $request->only([
                        'status',
                        'space_id',
                        'date'
                    ]),

                'spaces'
                    => Space::all(),
            ]
        );
    }

    public function confirm(
        Reservation $reservation
    )
    {
        $reservation->update([
            'status'
                =>
                'confirmed'
        ]);

        Mail::to(
            $reservation
                ->user_email
        )->send(

            new
            ReservationConfirmedMail(
                $reservation
            )
        );

        return redirect(
            '/admin/reservations'
        );
    }
    
    public function reject(
        Request $request,
        Reservation $reservation
    )
    {
        $request->validate([

            'admin_reason'
                =>
                'required|string|max:1000'
        ]);

        $reservation->update([

            'status'
                =>
                'rejected',

            'admin_reason'
                =>
                $request
                    ->admin_reason
        ]);

        Mail::to(
            $reservation
                ->user_email
        )->send(

            new
            ReservationRejectedMail(
                $reservation
            )
        );

        return redirect(
            '/admin/reservations'
        );
    }

    public function cancel(
        Request $request,
        Reservation $reservation
    )
    {
        $request->validate([

            'admin_reason'
                =>
                'required|string|max:1000'
        ]);

        $reservation->update([

            'status'
                =>
                'cancelled',

            'admin_reason'
                =>
                $request
                    ->admin_reason
        ]);

        Mail::to(
            $reservation
                ->user_email
        )->send(

            new
            ReservationCancelledMail(
                $reservation
            )
        );

        return redirect(
            '/admin/reservations'
        );
    }

    public function finish(
        Reservation $reservation
    )
    {
        $reservation->update([

            'status'
                =>
                'finished'
        ]);

        Mail::to(
            $reservation
                ->user_email
        )->send(

            new
            ReservationFinishedMail(
                $reservation
            )
        );

        return redirect(
            '/admin/reservations'
        );
    }
}
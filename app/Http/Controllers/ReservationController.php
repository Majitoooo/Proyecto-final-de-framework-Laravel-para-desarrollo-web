<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReservationService;
use App\Models\Space;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationPendingMail;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(
        ReservationService $reservationService
    )
    {
        $this->reservationService = $reservationService;
    }

    public function create(Request $request)
    {
        $space = Space::findOrFail($request->space_id);

        return Inertia::render('Reservations/Create', [

            'space' => $space,

            'start_time' => $request->start_time,

            'end_time' => $request->end_time,
        ]);
    }

    public function store(Request $request)
    {
        try {

            $data = $request->validate([

                'space_id' => 'required|exists:spaces,id',

                'start_time' => 'required|date',

                'end_time' => 'required|date|after:start_time',

                'user_name' => 'required|string',

                'user_email' => 'required|email',

                'notes' => 'nullable|string',
            ]);

            $reservation =
                $this
                    ->reservationService
                    ->createReservation(
                        $data
                    );

            Mail::to(
                $reservation
                    ->user_email
            )->send(

                new
                ReservationPendingMail(
                    $reservation
                )
            );

            $space = Space::find($data['space_id']);

            return redirect('/spaces/' . $space->slug)
                ->with(
                    'success',
                    'Reserva creada exitosamente'
                );

        } catch (\Exception $e) {

            return back()->withErrors([

                'reservation' => $e->getMessage()
            ]);
        }
    }
}
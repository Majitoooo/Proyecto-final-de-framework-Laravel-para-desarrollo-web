<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReservationService;
use App\Models\Space;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationPendingMail;
use Carbon\Carbon;

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
        $space = Space::find($request->space_id);

        if (!$space) {
            return redirect()->back()
                ->with('error', 'Espacio no encontrado');
        }

        $startTime = $request->start_time;

        $endTime = Carbon::parse($startTime)
            ->addHour()
            ->toDateTimeString();

        return Inertia::render('Reservations/Create', [
            'space' => $space,
            'start_time' => $startTime,
            'end_time' => $endTime,
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

            if (
                auth()->check()
            ) {

                $data['user_id'] =
                    auth()->id();

                $data['user_name'] =
                    auth()->user()->name;

                $data['user_email'] =
                    auth()->user()->email;
            }

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

            return redirect()
                ->route(
                    'my.reservations'
                )
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
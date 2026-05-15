<?php

namespace App\Http\Controllers;

use App\Models\Space;
use Illuminate\Http\Request;
use App\Services\ReservationService;
use Inertia\Inertia;

class SpaceController extends Controller
{
    public function index()
    {
        $spaces =
            Space::where(
                'is_active',
                true
            )->get();

        return Inertia::render(
            'Spaces/Index',
            [
                'spaces'
                    => $spaces
            ]
        );
    }

    public function show(
        Request $request,
        Space $space,
        ReservationService $reservationService
    ) {
        $date = $request->query('date') ?? now()->toDateString();

        $slots = $reservationService->getAvailableSlots($space, $date);

        // Próximos 7 días con al menos un slot disponible
        $nextSlots = [];

        for ($i = 1; $i <= 7; $i++) {

            $nextDate = now()->addDays($i)->toDateString();

            $daySlots = $reservationService->getAvailableSlots(
                $space,
                $nextDate
            );

            if (count($daySlots)) {
                $nextSlots[] = [
                    'date'  => $nextDate,
                    'slots' => $daySlots,
                ];
            }
        }

        return Inertia::render('Spaces/Show', [
            'space'      => $space,
            'date'       => $date,
            'slots'      => $slots,
            'nextSlots'  => $nextSlots,
            'start_time' => $request->query('start_time'),
        ]);
    }

    public function availability(
        Space $space,
        Request $request,
        ReservationService $reservationService
    ) {
        $date = $request->query('date');

        $slots = $reservationService->getAvailableSlots($space, $date);

        return response()->json($slots);
    }


}
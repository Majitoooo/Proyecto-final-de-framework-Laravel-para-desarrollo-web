<?php

namespace App\Http\Controllers;

use App\Models\Space;
use Illuminate\Http\Request;
use App\Services\ReservationService;

class SpaceController extends Controller
{
    public function show(Space $space)
    {
        return response()->json($space);
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
<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalendarPublicController
{
    public function show(
        Request $request,
        ReservationService $reservationService
    )
    {
        $spaces =
            Space::where(
                'is_active',
                true
            )
            ->select([
                'id',
                'name',
                'slug'
            ])
            ->get();

        $spaceId =
            $request->space_id;

        $date =
            $request->date
            ??
            now()->toDateString();

        $slots = [];

        if ($spaceId) {

            $space =
                Space::find(
                    $spaceId
                );

            if ($space) {

                $slots =
                    $reservationService
                        ->getAvailableSlots(
                            $space,
                            $date
                        );
            }
        }

        return Inertia::render(
            'Calendar/Public',
            [

                'spaces'
                    => $spaces,

                'selectedSpaceId'
                    => $spaceId,

                'date'
                    => $date,

                'slots'
                    => $slots,
            ]
        );
    }
}
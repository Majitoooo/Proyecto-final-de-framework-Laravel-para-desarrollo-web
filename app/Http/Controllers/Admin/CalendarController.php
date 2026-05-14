<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Space;
use App\Models\Reservation;
use App\Models\BlockedSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalendarController
    extends Controller
{
    public function show(
        Request $request
    )
    {
        $spaceId =
            $request->space_id;

        $spaces =
            Space::where(
                'is_active',
                true
            )->get();

        $startOfWeek =
            Carbon::parse(
                $request->week
                ??
                now()
            )->startOfWeek();

        $selectedSpace =
            null;

        $availabilities =
            collect();

        $reservations =
            collect();

        $blockedSlots =
            collect();

        if ($spaceId) {

            $selectedSpace =
                Space::find(
                    $spaceId
                );

            if (
                $selectedSpace
            ) {

                $availabilities =
                    $selectedSpace
                        ->availabilities;
                
                $reservations =
                    Reservation::where(
                        'space_id',
                        $spaceId
                    )
                    ->whereBetween(
                        'start_time',
                        [
                            $startOfWeek
                                ->copy()
                                ->startOfDay(),

                            $startOfWeek
                                ->copy()
                                ->endOfWeek()
                                ->endOfDay()
                        ]
                    )
                    ->get();

                $blockedSlots =
                    BlockedSlot::where(
                        'space_id',
                        $spaceId
                    )
                    ->whereBetween(
                        'start_time',
                        [
                            $startOfWeek
                                ->copy()
                                ->startOfDay(),

                            $startOfWeek
                                ->copy()
                                ->endOfWeek()
                                ->endOfDay()
                        ]
                    )
                    ->get();
            }
        }

        $weekDays = [];

        for (
            $i = 0;
            $i < 7;
            $i++
        ) {

            $weekDays[] =
                $startOfWeek
                    ->copy()
                    ->addDays($i)
                    ->toDateString();
        }

        return Inertia::render(
            'Admin/Calendar/Show',
            [

                'spaces'
                    => $spaces,

                'selectedSpaceId'
                    => $spaceId,

                'selectedSpace'
                    => $selectedSpace,

                'availabilities'
                    => $availabilities,

                'weekDays'
                    => $weekDays,

                'currentWeek'
                    => $startOfWeek
                        ->toDateString(),
                
                'reservations'
                    => $reservations,

                'blockedSlots'
                    => $blockedSlots,
            ]
        );
    }
}
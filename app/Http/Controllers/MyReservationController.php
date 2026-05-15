<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class MyReservationController
{
    public function index()
    {
        $reservations =
            auth()
                ->user()
                ->reservations()
                ->with('space')
                ->latest()
                ->get();

        return Inertia::render(
            'Reservations/MyReservations',
            [

                'reservations'
                    =>
                    $reservations
            ]
        );
    }
}
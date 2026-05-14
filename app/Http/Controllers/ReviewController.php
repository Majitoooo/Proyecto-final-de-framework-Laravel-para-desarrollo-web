<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReviewController
    extends Controller
{
    public function create(
        Reservation $reservation
    )
    {
        // Solo finalizadas

        if (
            $reservation->status
            !==
            'finished'
        ) {

            abort(403);
        }

        // Solo una review

        if (
            $reservation->review
        ) {

            return Inertia::render(
                'Reviews/AlreadyReviewed'
            );
        }

        return Inertia::render(
            'Reviews/Create',
            [

                'reservation'
                    =>
                    $reservation
                        ->load(
                            'space'
                        )
            ]
        );
    }

    public function store(
        Request $request,
        Reservation $reservation
    )
    {
        // Solo una review

        if (
            $reservation->review
        ) {

            return back()
                ->withErrors([
                    'review'
                    =>
                    'Esta reserva ya fue calificada'
                ]);
        }

        $data =
            $request->validate([

                'rating'
                    =>
                    'required|integer|min:1|max:5',

                'comment'
                    =>
                    'nullable|string|max:1000',
            ]);

        Review::create([

            'reservation_id'
                =>
                $reservation->id,

            'space_id'
                =>
                $reservation->space_id,

            'rating'
                =>
                $data['rating'],

            'comment'
                =>
                $data['comment']
        ]);

        return Inertia::render(
            'Reviews/Thanks'
        );
    }
}
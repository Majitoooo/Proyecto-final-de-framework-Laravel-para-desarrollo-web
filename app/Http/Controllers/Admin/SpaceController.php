<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Space;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\ReservationService;
use App\Models\Availability;

class SpaceController extends Controller
{
    public function index()
    {
        $spaces = Space::where(
            'is_active',
            true
        )->get();

        return Inertia::render(
            'Spaces/Index',
            [
                'spaces' => $spaces
            ]
        );
    }

    public function create()
    {
        return Inertia::render('Admin/Spaces/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([

            'name' => 'required|string|max:255',

            'type' => 'required|string|max:255',

            'capacity' => 'required|integer|min:1',

            'description' => 'nullable|string',

            'price_per_hour' => 'nullable|numeric|min:0',

            'is_active' => 'required|boolean',

            'usage_rules' => 'nullable|string',

            'availability' => 'required|array',

            'availability.*.day_of_week'
                => 'required|integer|min:0|max:6',

            'availability.*.start_time'
                => 'nullable',

            'availability.*.end_time'
                => 'nullable',
        ]);

        $space = Space::create([

            'name' => $data['name'],

            'type' => $data['type'],

            'capacity' => $data['capacity'],

            'description' => $data['description'],
                        
            'usage_rules'
                => $data['usage_rules'],

            'price_per_hour'
                => $data['price_per_hour'],

            'is_active'
                => $data['is_active'],

        ]);

        foreach (
            $data['availability']
            as $availability
        ) {

            if (
                $availability['start_time']
                &&
                $availability['end_time']
            ) {

                Availability::create([

                    'space_id'
                        => $space->id,

                    'day_of_week'
                        => $availability['day_of_week'],

                    'start_time'
                        => $availability['start_time'],

                    'end_time'
                        => $availability['end_time'],
                ]);
            }
        }

        return redirect()
            ->route(
                'admin.spaces.index'
            )
            ->with(
                'success',
                'Espacio creado correctamente'
            );
    }

    public function edit(Space $space)
    {
        $space->load('availabilities');

        return Inertia::render(
            'Admin/Spaces/Edit',
            [
                'space' => $space
            ]
        );
    }

    public function update(
        Request $request,
        Space $space
    )
    {
        $data = $request->validate([

            'name'
                => 'required|string|max:255',

            'type'
                => 'required|string|max:255',

            'capacity'
                => 'required|integer|min:1',

            'description'
                => 'nullable|string',
                        
            'usage_rules'
                => 'nullable|string',

            'price_per_hour'
                => 'nullable|numeric|min:0',

            'is_active'
                => 'required|boolean',

            'availability'
                => 'required|array',

            'availability.*.day_of_week'
                => 'required|integer|min:0|max:6',

            'availability.*.start_time'
                => 'nullable',

            'availability.*.end_time'
                => 'nullable',
        ]);

        // Actualizar espacio
        $space->update([

            'name'
                => $data['name'],

            'type'
                => $data['type'],

            'capacity'
                => $data['capacity'],

            'description'
                => $data['description'],

            'usage_rules'
                => $data['usage_rules'],

            'price_per_hour'
                => $data['price_per_hour'],

            'is_active'
                => $data['is_active'],
        ]);

        // Borrar disponibilidad vieja
        $space->availabilities()
            ->delete();

        // Crear nueva disponibilidad
        foreach (
            $data['availability']
            as $availability
        ) {

            if (
                $availability['start_time']
                &&
                $availability['end_time']
            ) {

                $space->availabilities()
                    ->create([

                        'day_of_week'
                            => $availability['day_of_week'],

                        'start_time'
                            => $availability['start_time'],

                        'end_time'
                            => $availability['end_time'],
                    ]);
            }
        }

        return redirect()
            ->route(
                'admin.spaces.index'
            )
            ->with(
                'success',
                'Espacio actualizado correctamente'
            );
    }

    public function toggle(Space $space)
    {
        $space->update([
            'is_active' => !$space->is_active
        ]);

        return redirect('/spaces');
    }

    public function show(
        Request $request,
        Space $space,
        ReservationService $reservationService
    )
    {
        $date = $request->get(
            'date',
            now()->toDateString()
        );

        $slots =
            $reservationService
                ->getAvailableSlots(
                    $space,
                    $date
                );

        $nextSlots =
            $reservationService
                ->getNextAvailableSlots(
                    $space
                );

        return Inertia::render(
            'Spaces/Show',
            [

                'space'
                    => $space,

                'date'
                    => $date,

                'slots'
                    => $slots,

                'nextSlots'
                    => $nextSlots,
            ]
        );
    }

    public function availability(
        Space $space,
        Request $request,
        ReservationService $reservationService
    )
    {
        $date = $request->query('date');

        $slots = $reservationService->getAvailableSlots(
            $space,
            $date
        );

        return response()->json($slots);
    }

    public function adminIndex()    
    {
        $spaces = Space::all();

        return Inertia::render(
            'Admin/Spaces/Index',
            [
                'spaces' => $spaces
            ]
        );
    }
}
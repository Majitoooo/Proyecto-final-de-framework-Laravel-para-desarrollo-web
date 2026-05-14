<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Space;
use Inertia\Inertia;
use App\Models\BlockedSlot;

class BlockedSlotController extends Controller
{
    public function create(
        Space $space
    )
    {
        return Inertia::render(
            'Admin/BlockedSlots/Create',
            [
                'space'
                    => $space
            ]
        );
    }

    public function store(
        Request $request,
        Space $space
    )
    {
        $data =
            $request->validate([

                'start_time'
                    => 'required|date',

                'end_time'
                    => 'required|date|after:start_time',

                'reason'
                    => 'nullable|string|max:255',
            ]);

        $space
            ->blockedSlots()
            ->create($data);

        return redirect()
            ->route(
                'admin.spaces.index'
            )
            ->with(
                'success',
                'Horario bloqueado'
            );
    }

    public function index(
        Space $space
    )
    {
        $blockedSlots =
            $space
                ->blockedSlots()
                ->latest()
                ->get();

        return Inertia::render(
            'Admin/BlockedSlots/Index',
            [

                'space'
                    => $space,

                'blockedSlots'
                    => $blockedSlots
            ]
        );
    }

    public function destroy(
        BlockedSlot $blockedSlot
    )
    {
        $blockedSlot->delete();

        return back()->with(
            'success',
            'Bloqueo eliminado'
        );
}
}

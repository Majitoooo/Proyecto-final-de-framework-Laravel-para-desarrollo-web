<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Availability;
use App\Models\BlockedSlot;
use Carbon\Carbon;

class ReservationService
{
    public function createReservation($data)
    {
        $spaceId = $data['space_id'];
        $start = $data['start_time'];
        $end = $data['end_time'];

        // 1. Disponibilidad
        $day = Carbon::parse($start)->dayOfWeek;

        $availability = Availability::where('space_id', $spaceId)
            ->where('day_of_week', $day)
            ->first();

        if (!$availability) {
            throw new \Exception('No hay disponibilidad para este día');
        }

        $startTime = Carbon::parse($start)->format('H:i:s');
        $endTime = Carbon::parse($end)->format('H:i:s');

        if (
            $startTime < $availability->start_time ||
            $endTime > $availability->end_time
        ) {
            throw new \Exception('Fuera del horario disponible');
        }

        // 2. Bloqueos
        $blocked = BlockedSlot::where('space_id', $spaceId)
            ->where(function ($q) use ($start, $end) {

                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->exists();

        if ($blocked) {
            throw new \Exception('Horario bloqueado');
        }

        // 3. Solapamiento
        $overlap = Reservation::where('space_id', $spaceId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($start, $end) {

                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->exists();

        if ($overlap) {
            throw new \Exception('Ya existe una reserva en ese horario');
        }

        // 4. Crear
        return Reservation::create([
            'space_id' => $spaceId,
            'start_time' => $start,
            'end_time' => $end,
            'status' => 'pending',
            'user_name' => $data['user_name'],
            'user_email' => $data['user_email'],
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public function getAvailableSlots($space, $date)
    {
        $day = Carbon::parse($date)->dayOfWeek;

        $availability = $space->availabilities()
            ->where('day_of_week', $day)
            ->first();

        // No hay disponibilidad
        if (!$availability) {
            return [];
        }

        $slotMinutes = (int) env('RESERVATION_SLOT_MINUTES', 60);

        $start = Carbon::parse(
            $date . ' ' . $availability->start_time
        );

        $end = Carbon::parse(
            $date . ' ' . $availability->end_time
        );

        // Reservas ocupadas
        $reservations = Reservation::where('space_id', $space->id)
            ->whereDate('start_time', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        // Bloqueos
        $blockedSlots = BlockedSlot::where('space_id', $space->id)
            ->whereDate('start_time', $date)
            ->get();

        $slots = [];

        while ($start < $end) {

            $slotEnd = $start->copy()->addMinutes($slotMinutes);

            // No mostrar horarios pasados

            if (
                $slotEnd <= now()
            ) {

                $start->addMinutes(
                    $slotMinutes
                );

                continue;
            }

            // Evitar salir del rango
            if ($slotEnd > $end) {
                break;
            }

            // Verificar reservas
            $isReserved = $reservations->contains(
                function ($reservation) use ($start, $slotEnd) {

                    return
                        $reservation->start_time < $slotEnd &&
                        $reservation->end_time > $start;
                }
            );

            // Verificar bloqueos
            $isBlocked = $blockedSlots->contains(
                function ($blocked) use ($start, $slotEnd) {

                    return
                        $blocked->start_time < $slotEnd &&
                        $blocked->end_time > $start;
                }
            );

            // Slot libre
            if (!$isReserved && !$isBlocked) {

                $slots[] = [

                    'start' => $start->format('H:i'),

                    'end' => $slotEnd->format('H:i'),

                    'full_start' => $start->format('Y-m-d H:i:s'),

                    'full_end' => $slotEnd->format('Y-m-d H:i:s'),
                ];
            }

            // Siguiente slot
            $start->addMinutes($slotMinutes);
        }

        return $slots;
    }

    public function getNextAvailableSlots(
        $space,
        $days = 7
    )
    {
        $availableSlots = [];

        for (
            $i = 0;
            $i < $days;
            $i++
        ) {

            $date = now()
                ->addDays($i)
                ->toDateString();

            $slots =
                $this->getAvailableSlots(
                    $space,
                    $date
                );

            if (
                count($slots)
            ) {

                $availableSlots[] = [

                    'date' => $date,

                    'slots'
                        => array_slice(
                            $slots,
                            0,
                            3
                        )
                ];
            }
        }

        return $availableSlots;
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Space;
use App\Models\Availability;

class AvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $spaces = Space::all();

        foreach ($spaces as $space) {

            for ($day = 1; $day <= 5; $day++) {

                Availability::create([
                    'space_id' => $space->id,
                    'day_of_week' => $day,
                    'start_time' => '08:00:00',
                    'end_time' => '18:00:00',
                ]);
            }
        }
    }
}
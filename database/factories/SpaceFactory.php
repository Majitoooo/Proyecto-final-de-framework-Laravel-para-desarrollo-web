<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SpaceFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [

            'name' => $name,

            'slug' => Str::slug($name),

            'type' => fake()->randomElement([
                'sala',
                'oficina',
                'auditorio'
            ]),

            'capacity' => fake()->numberBetween(2, 20),

            'description' => fake()->sentence(),

            'price_per_hour' => fake()->numberBetween(20, 200),

            'is_active' => true,
        ];
    }
}
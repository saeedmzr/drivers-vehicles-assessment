<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Models\Vehicle;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'plate_number' => strtoupper($this->faker->bothify('???-####')),
            'brand' => $this->faker->randomElement(['Toyota', 'Honda', 'Ford', 'BMW', 'Mercedes']),
            'model' => $this->faker->word(),
            'year' => $this->faker->numberBetween(2015, 2025),
        ];
    }
}

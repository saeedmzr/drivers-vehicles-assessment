<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Models\Driver;

class DriverFactory extends Factory
{
    protected $model = Driver::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'license_number' => strtoupper($this->faker->bothify('DL########')),
            'phone_number' => $this->faker->phoneNumber(),
        ];
    }
}

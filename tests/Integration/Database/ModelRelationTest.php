<?php

namespace Tests\Integration\Database;

use Models\Driver;
use Models\Vehicle;
use Tests\Integration\IntegrationTestCase;

class ModelRelationTest extends IntegrationTestCase
{
    /** @test */
    public function driver_can_have_many_vehicles(): void
    {
        $driver = Driver::factory()->create();
        $vehicle1 = Vehicle::factory()->create();
        $vehicle2 = Vehicle::factory()->create();

        $driver->vehicles()->attach($vehicle1->id, [
            'assigned_at' => now(),
            'is_active' => true,
        ]);
        $driver->vehicles()->attach($vehicle2->id, [
            'assigned_at' => now(),
            'is_active' => true,
        ]);

        $this->assertCount(2, $driver->vehicles);
        $this->assertTrue($driver->vehicles->contains($vehicle1));
        $this->assertTrue($driver->vehicles->contains($vehicle2));
    }

    /** @test */
    public function vehicle_can_have_many_drivers(): void
    {
        $vehicle = Vehicle::factory()->create();
        $driver1 = Driver::factory()->create();
        $driver2 = Driver::factory()->create();

        $vehicle->drivers()->attach($driver1->id, [
            'assigned_at' => now(),
            'is_active' => true,
        ]);
        $vehicle->drivers()->attach($driver2->id, [
            'assigned_at' => now()->subDays(1),
            'is_active' => false,
            'released_at' => now(),
        ]);

        $this->assertCount(2, $vehicle->drivers);
    }

    /** @test */
    public function driver_can_get_active_vehicles_only(): void
    {
        $driver = Driver::factory()->create();
        $activeVehicle = Vehicle::factory()->create();
        $inactiveVehicle = Vehicle::factory()->create();

        $driver->vehicles()->attach($activeVehicle->id, [
            'assigned_at' => now(),
            'is_active' => true,
        ]);
        $driver->vehicles()->attach($inactiveVehicle->id, [
            'assigned_at' => now()->subDays(1),
            'is_active' => false,
            'released_at' => now(),
        ]);

        $activeVehicles = $driver->activeVehicles;

        $this->assertCount(1, $activeVehicles);
        $this->assertTrue($activeVehicles->contains($activeVehicle));
        $this->assertFalse($activeVehicles->contains($inactiveVehicle));
    }

    /** @test */
    public function pivot_data_is_stored_correctly(): void
    {
        $driver = Driver::factory()->create();
        $vehicle = Vehicle::factory()->create();
        $assignedAt = now();
        $notes = 'Primary vehicle';

        $driver->vehicles()->attach($vehicle->id, [
            'assigned_at' => $assignedAt,
            'is_active' => true,
            'notes' => $notes,
        ]);

        $pivot = $driver->vehicles->first()->pivot;

        $this->assertEquals($assignedAt->format('Y-m-d'), $pivot->assigned_at->format('Y-m-d'));
        $this->assertTrue($pivot->is_active);
        $this->assertEquals($notes, $pivot->notes);
    }
}

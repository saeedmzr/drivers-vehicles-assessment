<?php

namespace Tests\Feature\Vehicle;

use Models\Driver;
use Models\Vehicle;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\FeatureTestCase;

class VehicleApiTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Vehicle::query()->forceDelete();
        Driver::query()->forceDelete();
    }

    #[Test]
    public function it_lists_vehicles_with_pagination(): void
    {
        Vehicle::query()->forceDelete();
        Vehicle::factory()->count(20)->create();

        $response = $this->apiGet('/api/vehicles?per_page=10');

        $this->assertSuccessResponse($response);
        $data = $response->json('data');

        $this->assertCount(10, $data['vehicles']);
        $this->assertEquals(20, $data['meta']['total']);
        $this->assertEquals(10, $data['meta']['per_page']);
    }

    #[Test]
    public function it_creates_a_vehicle(): void
    {
        $vehicleData = [
            'plateNumber' => 'ABC-1234',
            'brand' => 'Toyota',
            'model' => 'Camry',
            'year' => 2023,
        ];

        $response = $this->apiPost('/api/vehicles', $vehicleData);

        $this->assertSuccessResponse($response, 201);


        $this->assertDatabaseHas('vehicles', [
            'plate_number' => 'ABC-1234',
            'brand' => 'Toyota',
        ]);
    }

    #[Test]
    public function it_validates_required_fields_on_create(): void
    {
        $response = $this->apiPost('/api/vehicles', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['plateNumber', 'brand', 'model', 'year']);
    }

    #[Test]
    public function it_shows_a_vehicle(): void
    {
        $vehicle = Vehicle::factory()->create();

        $response = $this->apiGet("/api/vehicles/{$vehicle->id}");

        $this->assertSuccessResponse($response);
        $response->assertJsonFragment([
            'id' => $vehicle->id,
            'plateNumber' => $vehicle->plate_number,
            'brand' => $vehicle->brand,
        ]);
    }

    #[Test]
    public function it_updates_a_vehicle(): void
    {
        $vehicle = Vehicle::factory()->create();
        $updateData = [
            'brand' => 'Honda',
        ];

        $response = $this->apiPut("/api/vehicles/{$vehicle->id}", $updateData);

        $this->assertSuccessResponse($response);

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'brand' => 'Honda',
        ]);
    }

    #[Test]
    public function it_deletes_a_vehicle(): void
    {
        $vehicle = Vehicle::factory()->create();

        $response = $this->apiDelete("/api/vehicles/{$vehicle->id}");

        $this->assertSuccessResponse($response);
        $this->assertSoftDeleted('vehicles', ['id' => $vehicle->id]);
    }

    #[Test]
    public function it_searches_vehicles_by_plate_number(): void
    {
        $vehicle1 = Vehicle::factory()->create(['plate_number' => 'XYZ-9999']);
        $vehicle2 = Vehicle::factory()->create(['plate_number' => 'ABC-1111']);

        $response = $this->apiGet('/api/vehicles?search=XYZ-9999');

        $this->assertSuccessResponse($response);
        $data = $response->json('data.vehicles');

        $this->assertGreaterThanOrEqual(1, count($data));
        $foundVehicle = collect($data)->firstWhere('plate_number', 'XYZ-9999');
        $this->assertNotNull($foundVehicle);
    }

    #[Test]
    public function it_filters_vehicles_with_drivers(): void
    {
        Vehicle::query()->forceDelete();
        Driver::query()->forceDelete();

        $vehicleWithDriver = Vehicle::factory()->create();
        $vehicleWithoutDriver = Vehicle::factory()->create();
        $driver = Driver::factory()->create();

        $vehicleWithDriver->drivers()->attach($driver->id, [
            'assigned_at' => now(),
            'is_active' => true,
        ]);

        $response = $this->apiGet('/api/vehicles?has_drivers=1');

        $this->assertSuccessResponse($response);
        $data = $response->json('data.vehicles');

        $this->assertCount(1, $data);

        $ids = collect($data)->pluck('id')->toArray();
        $this->assertContains($vehicleWithDriver->id, $ids);
        $this->assertNotContains($vehicleWithoutDriver->id, $ids);
    }
}

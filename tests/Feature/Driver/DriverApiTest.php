<?php

namespace Tests\Feature\Driver;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Models\Driver;
use Models\Vehicle;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\FeatureTestCase;

class DriverApiTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Driver::query()->forceDelete();
        Vehicle::query()->forceDelete();
    }
    #[Test]
    public function it_lists_drivers_with_pagination(): void
    {
        // Clean slate
        Driver::query()->forceDelete();

        // Create exactly 20 drivers
        Driver::factory()->count(20)->create();

        $response = $this->apiGet('/api/drivers?per_page=10');

        $this->assertSuccessResponse($response);
        $data = $response->json('data');

        $this->assertCount(10, $data['drivers']);
        $this->assertEquals(20, $data['meta']['total']);
        $this->assertEquals(10, $data['meta']['per_page']);
    }

    #[Test]
    public function it_creates_a_driver(): void
    {
        $driverData = [
            'name' => 'John Doe',
            'licenseNumber' => 'DL123456789',
            'phoneNumber' => '+1234567890',
        ];

        $response = $this->apiPost('/api/drivers', $driverData);

        $this->assertSuccessResponse($response, 201);
        $response->assertJsonFragment([
            'name' => 'John Doe',
            'license_number' => 'DL123456789',
        ]);

        $this->assertDatabaseHas('drivers', [
            'name' => 'John Doe',
            'license_number' => 'DL123456789',
        ]);
    }

    #[Test]
    public function it_validates_required_fields_on_create(): void
    {
        $response = $this->apiPost('/api/drivers', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'licenseNumber', 'phoneNumber']);
    }

    #[Test]
    public function it_shows_a_driver(): void
    {
        $driver = Driver::factory()->create();

        $response = $this->apiGet("/api/drivers/{$driver->id}");

        $this->assertSuccessResponse($response);
        $response->assertJsonFragment([
            'id' => $driver->id,
            'name' => $driver->name,
            'license_number' => $driver->license_number,
        ]);
    }

    #[Test]
    public function it_updates_a_driver(): void
    {
        $driver = Driver::factory()->create();
        $updateData = [
            'name' => 'Updated Name',
            'phoneNumber' => '0987654321',
        ];

        $response = $this->apiPut("/api/drivers/{$driver->id}", $updateData);

        $this->assertSuccessResponse($response);

        // Check database directly
        $this->assertDatabaseHas('drivers', [
            'id' => $driver->id,
            'name' => 'Updated Name',
            'phone_number' => '0987654321',
        ]);
    }

    #[Test]
    public function it_deletes_a_driver(): void
    {
        $driver = Driver::factory()->create();

        $response = $this->apiDelete("/api/drivers/{$driver->id}");

        $this->assertSuccessResponse($response);
        $this->assertSoftDeleted('drivers', ['id' => $driver->id]);
    }

    #[Test]
    public function it_assigns_vehicle_to_driver(): void
    {
        $driver = Driver::factory()->create();
        $vehicle = Vehicle::factory()->create();

        $response = $this->apiPost("/api/drivers/{$driver->id}/assign-vehicle", [
            'vehicle_id' => $vehicle->id,
            'notes' => 'Primary vehicle',
        ]);

        $this->assertSuccessResponse($response);
        $this->assertDatabaseHas('driver_vehicle', [
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
            'is_active' => true,
        ]);
    }

    #[Test]
    public function it_searches_drivers_by_name(): void
    {
        $driver1 = Driver::factory()->create(['name' => 'John Doe Unique']);
        $driver2 = Driver::factory()->create(['name' => 'Jane Smith']);

        $response = $this->apiGet('/api/drivers?search=John Doe Unique');

        $this->assertSuccessResponse($response);
        $data = $response->json('data.drivers');

        $this->assertGreaterThanOrEqual(1, count($data));
        $foundJohn = collect($data)->firstWhere('name', 'John Doe Unique');
        $this->assertNotNull($foundJohn);
    }

    #[Test]
    public function it_filters_drivers_with_vehicles(): void
    {
        // Clean slate
        Driver::query()->forceDelete();
        Vehicle::query()->forceDelete();

        $driverWithVehicle = Driver::factory()->create();
        $driverWithoutVehicle = Driver::factory()->create();
        $vehicle = Vehicle::factory()->create();

        $driverWithVehicle->vehicles()->attach($vehicle->id, [
            'assigned_at' => now(),
            'is_active' => true,
        ]);

        $response = $this->apiGet('/api/drivers?has_vehicles=1');

        $this->assertSuccessResponse($response);
        $data = $response->json('data.drivers');

        // Should only return 1 driver (the one with vehicle)
        $this->assertCount(1, $data);

        $ids = collect($data)->pluck('id')->toArray();
        $this->assertContains($driverWithVehicle->id, $ids);
        $this->assertNotContains($driverWithoutVehicle->id, $ids);
    }
}

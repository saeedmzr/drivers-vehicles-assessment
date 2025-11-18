<?php

namespace Tests\Integration\Database;

use Illuminate\Support\Facades\Schema;
use Tests\Integration\IntegrationTestCase;

class MigrationTest extends IntegrationTestCase
{
    /** @test */
    public function it_has_drivers_table_with_correct_columns(): void
    {
        $this->assertTrue(Schema::hasTable('drivers'));
        $this->assertTrue(Schema::hasColumns('drivers', [
            'id',
            'name',
            'license_number',
            'phone_number',
            'created_at',
            'updated_at',
            'deleted_at',
        ]));
    }

    /** @test */
    public function it_has_vehicles_table_with_correct_columns(): void
    {
        $this->assertTrue(Schema::hasTable('vehicles'));
        $this->assertTrue(Schema::hasColumns('vehicles', [
            'id',
            'plate_number',
            'brand',
            'model',
            'year',
            'created_at',
            'updated_at',
            'deleted_at',
        ]));
    }

    /** @test */
    public function it_has_driver_vehicle_pivot_table(): void
    {
        $this->assertTrue(Schema::hasTable('driver_vehicle'));
        $this->assertTrue(Schema::hasColumns('driver_vehicle', [
            'id',
            'driver_id',
            'vehicle_id',
            'assigned_at',
            'released_at',
            'is_active',
            'notes',
            'created_at',
            'updated_at',
        ]));
    }

    /** @test */
    public function it_has_users_table_with_correct_columns(): void
    {
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasColumns('users', [
            'id',
            'name',
            'email',
            'password',
            'created_at',
            'updated_at',
        ]));
    }

    /** @test */
    public function it_has_sessions_table(): void
    {
        $this->assertTrue(Schema::hasTable('sessions'));
        $this->assertTrue(Schema::hasColumns('sessions', [
            'id',
            'user_id',
            'ip_address',
            'user_agent',
            'payload',
            'last_activity',
        ]));
    }
}

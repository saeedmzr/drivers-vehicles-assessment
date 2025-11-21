<?php

namespace Models;

use Database\Factories\AdminFactory;
use Database\Factories\DriverFactory;
use Domain\Driver\Entities\DriverEntity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'license_number',
        'phone_number',
    ];
    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'driver_vehicle')
            ->using(DriverVehicle::class)
            ->withPivot(['assigned_at', 'released_at', 'is_active', 'notes'])
            ->withTimestamps();
    }

    public function activeVehicles(): BelongsToMany
    {
        return $this->vehicles()
            ->using(DriverVehicle::class)
            ->wherePivot('is_active', true);
    }

    public function toEntity(): DriverEntity
    {
        return new DriverEntity(
            id: $this->id,
            name: $this->name,
            licenseNumber: $this->license_number,
            phoneNumber: $this->phone_number,
            vehicles: $this->vehicles ? $this->vehicles->map(fn($vehicle) => $vehicle->toEntity())->toArray() : [],
            createdAt: $this->created_at,
            updatedAt: $this->updated_at,
            deletedAt: $this->deleted_at
        );
    }
    protected static function newFactory(): DriverFactory
    {
        return DriverFactory::new();
    }
}

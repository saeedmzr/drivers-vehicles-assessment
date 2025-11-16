<?php

namespace Models;

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

    /**
     * Get the vehicles assigned to this driver
     */
    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'driver_vehicle')
            ->withPivot(['assigned_at', 'released_at', 'is_active', 'notes'])
            ->withTimestamps();
    }

    /**
     * Get only active vehicles for this driver
     */
    public function activeVehicles(): BelongsToMany
    {
        return $this->vehicles()->wherePivot('is_active', true);
    }

    /**
     * Convert model to domain entity
     */
    public function toEntity(): DriverEntity
    {
        return new DriverEntity(
            id: $this->id,
            name: $this->name,
            licenseNumber: $this->license_number,
            phoneNumber: $this->phone_number,
            createdAt: $this->created_at,
            updatedAt: $this->updated_at,
            deletedAt: $this->deleted_at
        );
    }
}

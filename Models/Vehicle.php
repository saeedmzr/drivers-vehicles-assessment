<?php

namespace Models;

use Domain\Vehicle\Entities\VehicleEntity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'plate_number',
        'brand',
        'model',
        'year',
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    /**
     * Get the drivers assigned to this vehicle
     */
    public function drivers(): BelongsToMany
    {
        return $this->belongsToMany(Driver::class, 'driver_vehicle')
            ->withPivot(['assigned_at', 'released_at', 'is_active', 'notes'])
            ->withTimestamps();
    }

    /**
     * Get the current active driver for this vehicle
     */
    public function currentDriver(): BelongsToMany
    {
        return $this->drivers()
            ->wherePivot('is_active', true)
            ->wherePivotNull('released_at');
    }

    /**
     * Check if vehicle is currently assigned
     */
    public function isAssigned(): bool
    {
        return $this->currentDriver()->exists();
    }

    /**
     * Convert model to domain entity
     */
    public function toEntity(): VehicleEntity
    {
        return new VehicleEntity(
            id: $this->id,
            plateNumber: $this->plate_number,
            brand: $this->brand,
            model: $this->model,
            year: $this->year,
            createdAt: $this->created_at,
            updatedAt: $this->updated_at,
            deletedAt: $this->deleted_at
        );
    }
}

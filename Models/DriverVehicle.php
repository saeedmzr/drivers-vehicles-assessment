<?php

namespace Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DriverVehicle extends Pivot
{

    use HasUuids;

    protected $table = 'driver_vehicle';

    protected $fillable = [
        'driver_id',
        'vehicle_id',
        'assigned_at',
        'released_at',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'released_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}

<?php

namespace Domain\Vehicle\Repositories;

use Domain\Core\BaseRepository;
use Domain\Vehicle\Contracts\VehicleRepositoryInterface;
use Models\Vehicle;

class VehicleRepository extends BaseRepository implements VehicleRepositoryInterface
{
    public function __construct(Vehicle $vehicle)
    {
        parent::__construct($vehicle);
    }

    /**
     * Get vehicle with current driver
     */
    public function findWithCurrentDriver(string $id)
    {
        return $this->model->with('currentDriver')->find($id);
    }

    /**
     * Get unassigned vehicles
     */
    public function getUnassigned()
    {
        return $this->model->whereDoesntHave('drivers', function ($query) {
            $query->wherePivot('is_active', true);
        })->get();
    }
}

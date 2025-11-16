<?php

namespace Domain\Driver\Repositories;

use Contracts\DriverRepositoryInterface;
use Domain\Core\BaseRepository;
use Models\Driver;

class DriverRepository extends BaseRepository implements DriverRepositoryInterface
{
    public function __construct(Driver $driver)
    {
        parent::__construct($driver);
    }

    /**
     * Get driver with active vehicles
     */
    public function findWithActiveVehicles(string $id)
    {
        return $this->model->with('activeVehicles')->find($id);
    }

    /**
     * Get drivers with their vehicles count
     */
    public function withVehiclesCount()
    {
        return $this->model->withCount('vehicles')->get();
    }
}

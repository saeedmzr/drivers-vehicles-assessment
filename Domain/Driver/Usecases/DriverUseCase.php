<?php

namespace Domain\Driver\Usecases;

use Domain\Core\Usecases\BaseUsecase;
use Domain\Driver\Contracts\DriverRepositoryInterface;
use Domain\Driver\Contracts\DriverUseCaseInterface;
use Domain\Driver\Exceptions\DriverNotFoundException;

class DriverUseCase extends BaseUsecase implements DriverUseCaseInterface
{
    public function __construct(DriverRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Find a vehicle by ID
     */
    public function findVehicle(string $vehicleId)
    {
        return \Models\Vehicle::find($vehicleId);
    }

    /**
     * Assign a vehicle to a driver
     */
    public function assignVehicle(string $driverId, string $vehicleId, array $pivotData = []): void
    {
        $driver = $this->repository->find($driverId);

        if (!$driver) {
            throw new DriverNotFoundException($driverId);
        }

        $driver->vehicles()->attach($vehicleId, $pivotData);
    }
}

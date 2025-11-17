<?php

namespace Domain\Driver\Contracts;

use Domain\Core\Contracts\BaseUsecaseInterface;

interface DriverUseCaseInterface extends BaseUsecaseInterface
{

    public function assignVehicle(string $driverId, string $vehicleId, array $pivotData = []): void;
}

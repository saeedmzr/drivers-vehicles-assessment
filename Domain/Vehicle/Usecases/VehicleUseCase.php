<?php

namespace Domain\Vehicle\Usecases;

use Domain\Core\Usecases\BaseUsecase;
use Domain\Vehicle\Contracts\VehicleRepositoryInterface;
use Domain\Vehicle\Contracts\VehicleUseCaseInterface;

class VehicleUseCase extends BaseUseCase implements VehicleUseCaseInterface
{
    public function __construct(VehicleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}

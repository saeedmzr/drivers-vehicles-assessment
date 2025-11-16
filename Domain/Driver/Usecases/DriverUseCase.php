<?php

namespace Usecases;

use Contracts\DriverRepositoryInterface;
use Contracts\DriverUseCaseInterface;
use Domain\Core\Usecases\BaseUsecase;

class DriverUseCase extends BaseUseCase implements DriverUseCaseInterface
{
    public function __construct(DriverRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}

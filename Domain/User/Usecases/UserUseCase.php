<?php

namespace Usecases;

use Domain\Core\Usecases\BaseUsecase;
use Domain\User\Contracts\UserRepositoryInterface;
use Domain\User\Contracts\UserUseCaseInterface;

class UserUseCase extends BaseUseCase implements UserUseCaseInterface
{
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->repository = $userRepository;
    }
}

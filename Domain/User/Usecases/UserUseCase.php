<?php

namespace Domain\User\Usecases;

use Domain\Core\Usecases\BaseUsecase;
use Domain\User\Contracts\UserRepositoryInterface;
use Domain\User\Contracts\UserUseCaseInterface;
use Illuminate\Support\Facades\Auth;
use Models\User;

class UserUseCase extends BaseUseCase implements UserUseCaseInterface
{
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->repository = $userRepository;
    }
    public function authenticate(string $email, string $password): User|null
    {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return Auth::user();
        }

        return null;
    }
}

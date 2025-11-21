<?php
namespace Domain\User\Contracts;
use Domain\Core\Contracts\BaseUsecaseInterface;
use Models\User;

interface UserUseCaseInterface extends BaseUsecaseInterface
{
    public function authenticate(string $email, string $password): User|null;

}

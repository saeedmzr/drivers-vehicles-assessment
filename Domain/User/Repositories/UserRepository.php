<?php

namespace Repositories;

use Domain\Core\BaseRepository;
use Domain\User\Contracts\UserRepositoryInterface;
use Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}

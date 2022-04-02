<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserData;
use App\Domain\User\Repository\UserRepository;

class UserCreator
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createUser(array $data): int
    {
        // validate input

        // any other task you need

        // insert
        $user = new UserData($data);

        return $this->repository->insertUser($user);
    }
}

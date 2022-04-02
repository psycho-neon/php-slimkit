<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UserData;
use Cycle\Database\DatabaseInterface;

class UserRepository
{
    private DatabaseInterface $database;

    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function insertUser(UserData $user): int
    {
        // convert to 2d array
        $row = UserData::toRow($user);

        return $this->database
            ->insert('users')
            ->values($row)
            ->run();
    }

    public function deleteUserById(int $id): bool
    {
        $query = $this->database
            ->delete('users')
            ->where('id', $id)
            ->run();

        return $query > 0;
    }
}

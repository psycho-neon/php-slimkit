<?php

declare(strict_types=1);

namespace App\Domain\User\Data;

use Selective\ArrayReader\ArrayReader;

class UserData
{
    public ?int $id;

    public ?string $username;

    public ?string $email;

    public function __construct(array $data)
    {
        $reader = new ArrayReader($data);

        $this->id = $reader->findInt('id');
        $this->username = $reader->findString('username');
        $this->email = $reader->findString('email');
    }

    /**
     * Convert array of data to UserData[].
     *
     * @param array $data
     *
     * @return UserData[]
     */
    public static function toList(array $data): array
    {
        $items = [];

        foreach ($data as $row) {
            $items[] = new UserData($row);
        }

        return $items;
    }

    /**
     * Convert UserData object to 2D array.
     *
     * @param UserData $user
     *
     * @return array|null
     */
    public static function toRow(UserData $user): ?array
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
        ];
    }
}

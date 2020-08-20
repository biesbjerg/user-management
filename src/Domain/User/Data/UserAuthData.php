<?php
declare(strict_types=1);

namespace App\Domain\User\Data;

use DateTime;

class UserAuthData
{
    public int $id;

    public string $username;

    public string $name;

    public DateTime $lastLogin;

    public static function fromArray(array $data): self
    {
        $user = new self();

        $user->id = (int) $data['id'];
        $user->name = (string) $data['name'];
        $user->username = (string) $data['username'];
        $user->lastLogin = new DateTime($data['last_login']);

        return $user;
    }
}

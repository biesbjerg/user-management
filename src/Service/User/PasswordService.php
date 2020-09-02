<?php
declare(strict_types=1);

namespace App\Service\User;

use RuntimeException;

class PasswordService
{
    public function isValid(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function hash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        if ($hash === false) {
            throw new RuntimeException('Error hashing password');
        }

        return $hash;
    }

    public function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, PASSWORD_DEFAULT);
    }
}

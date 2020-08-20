<?php
declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserAuthRepository;

class UserAuthService
{
    private UserAuthRepository $repository;

    public function __construct(UserAuthRepository $repository)
    {
        $this->repository = $repository;
    }

    public function authenticate(string $username, string $password): ?array
    {
        $row = $this->repository->findUserByUsername($username);
        if (!$row) {
            return null;
        }

        if (!password_verify($password, $row['password'])) {
            return null;
        }

        return $row;
    }

    public function updateLastLogin(int $userId): bool
    {
        return (bool) $this->repository->touchLastLogin($userId);
    }
}

<?php
declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserSessionData;
use App\Domain\User\Repository\UserReadRepository;
use App\Domain\User\Repository\UserUpdateRepository;

class UserAuthService
{
    private UserReadRepository $userReadRepository;

    private UserUpdateRepository $userUpdateRepository;

    public function __construct(
        UserReadRepository $userReadRepository,
        UserUpdateRepository $userUpdateRepository
    ) {
        $this->userReadRepository = $userReadRepository;
        $this->userUpdateRepository = $userUpdateRepository;
    }

    public function authenticate(string $username, string $password): ?UserSessionData
    {
        $row = $this->userReadRepository->findByUsername($username);
        if (!$row) {
            return null;
        }
        if (!$row['is_enabled']) {
            return null;
        }
        if (!password_verify($password, $row['password'])) {
            return null;
        }

        return UserSessionData::fromArray($row);
    }

    public function updateLastLogin(int $userId): bool
    {
        return $this->userUpdateRepository->updateLastLogin($userId);
    }
}

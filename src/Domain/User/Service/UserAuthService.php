<?php
declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserSessionData;
use App\Domain\User\Repository\UserReadRepository;
use App\Domain\User\Repository\UserUpdateRepository;
use Odan\Session\SessionInterface as Session;

class UserAuthService
{
    private UserReadRepository $userReadRepository;

    private UserUpdateRepository $userUpdateRepository;

    private Session $session;

    public function __construct(
        UserReadRepository $userReadRepository,
        UserUpdateRepository $userUpdateRepository,
        Session $sessesion
    ) {
        $this->userReadRepository = $userReadRepository;
        $this->userUpdateRepository = $userUpdateRepository;
        $this->session = $sessesion;
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

    public function isAuthenticated(): bool
    {
        return (bool) $this->getUser();
    }

    public function setUser(UserSessionData $data): void
    {
        $this->session->set('User', $data);
    }

    public function getUser(): ?UserSessionData
    {
        return $this->session->get('User');
    }

    public function logout(): void
    {
        if ($this->session->has('User')) {
            $this->session->remove('User');
        }
    }

    public function updateLastLogin(int $userId): bool
    {
        return $this->userUpdateRepository->updateLastLogin($userId);
    }
}

<?php
declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserSessionData;
use App\Datasource\User\UserRepository;
use Odan\Session\SessionInterface as Session;

class AuthService
{
    private UserRepository $repository;

    private Session $session;

    public function __construct(UserRepository $repository, Session $session)
    {
        $this->repository = $repository;
        $this->session = $session;
    }

    public function authenticate(string $username, string $password): ?UserSessionData
    {
        $row = $this->repository->findByUsername($username, [
            'id',
            'username',
            'password',
            'name',
            'is_enabled',
            'last_login'
        ]);
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
        return $this->repository->updateLastLogin($userId);
    }
}

<?php
declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserAuthRepository;
use Odan\Session\SessionInterface as Session;

class UserAuthService
{
    private UserAuthRepository $repository;

    private Session $session;

    public function __construct(UserAuthRepository $repository, Session $session)
    {
        $this->repository = $repository;
        $this->session = $session;
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

    public function isAuthenticated(): bool
    {
        return (bool) $this->getUser();
    }

    public function setUser(array $data): void
    {
        $this->session->set('Auth', $data);
    }

    public function getUser(): ?array
    {
        return $this->session->get('Auth');
    }

    public function clearUser(): void
    {
        if ($this->session->has('Auth')) {
            $this->session->remove('Auth');
        }
    }
}

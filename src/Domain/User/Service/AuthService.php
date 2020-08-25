<?php
declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Datasource\User\UserRecord;
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

    public function authenticate(string $username, string $password): ?UserRecord
    {
        $user = $this->repository->findByUsername($username, [
            'id',
            'username',
            'password',
            'name',
            'is_enabled',
            'last_login'
        ]);
        if (!$user) {
            return null;
        }
        if (!$user->is_enabled) {
            return null;
        }
        if (!password_verify($password, $user->password)) {
            return null;
        }

        return $user;
    }

    public function isAuthenticated(): bool
    {
        return (bool) $this->getUser();
    }

    public function setUser(UserRecord $user): void
    {
        // Don't store password in session, even though it is hashed
        unset($user->password);

        $this->session->set('User', $user);
    }

    public function getUser(): ?UserRecord
    {
        return $this->session->get('User');
    }

    public function clearUser(): void
    {
        if ($this->session->has('User')) {
            $this->session->remove('User');
        }
    }

    public function updateLastLogin(UserRecord $user): bool
    {
        return $this->repository->updateLastLogin((int) $user->id);
    }
}

<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Datasource\User\UserRecord;
use App\Datasource\User\UserRecordFactory;
use App\Datasource\User\UserRepository;
use Odan\Session\SessionInterface as Session;

class AuthService
{
    private UserRepository $userRepository;

    private UserRecordFactory $userFactory;

    private PasswordService $passwordService;

    private Session $session;

    public function __construct(
        UserRepository $userRepository,
        UserRecordFactory $userFactory,
        PasswordService $passwordService,
        Session $session
    ) {
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->passwordService = $passwordService;
        $this->session = $session;
    }

    public function authenticate(string $username, string $password): ?UserRecord
    {
        $row = $this->userRepository->findByUsername($username, [
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
        if (!$this->passwordService->isValid($password, $row['password'])) {
            return null;
        }

        if ($this->passwordService->needsRehash($row['password'])) {
            $this->userRepository->updatePassword($row['id'], $this->passwordService->hash($password));
        }

        return $this->userFactory->newRecord($row);
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

    /**
     * Undocumented function
     *
     * @param string|int $id
     * @return boolean
     */
    public function updateLastLogin($id): bool
    {
        return $this->userRepository->updateLastLogin($id);
    }
}

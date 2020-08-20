<?php
declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserAuthData;
use Odan\Session\SessionInterface as Session;

class UserSessionService
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function set(UserAuthData $user): void
    {
        $this->session->set('User', $user);
    }

    public function get(): ?UserAuthData
    {
        return $this->session->get('User');
    }

    public function clear(): void
    {
        if ($this->session->has('User')) {
            $this->session->remove('User');
        }
    }

    public function isAuthenticated(): bool
    {
        return (bool) $this->get();
    }
}

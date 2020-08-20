<?php
declare(strict_types=1);

namespace App\Domain\User\Repository;

use Cake\Database\Connection;
use DateTime;

class UserAuthRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findUserByUsername(string $username): array
    {
        $query = $this->connection->newQuery()
            ->from('users')
            ->select([
                'id',
                'username',
                'password',
                'name',
                'last_login'
            ])
            ->where([
                'username' => $username,
                'is_enabled' => 1
            ]);

        $row = $query->execute()->fetch('assoc');

        return $row ?: [];
    }

    public function touchLastLogin(int $userId): bool
    {
        return (bool) $this->connection->update(
            'users',
            ['last_login' => new DateTime('now')],
            ['id' => $userId],
            ['last_login' => 'datetime']
        );
    }
}

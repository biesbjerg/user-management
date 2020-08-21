<?php
declare(strict_types=1);

namespace App\Domain\User\Repository;

use Cake\Database\Connection;
use DateTime;

class UserUpdateRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->connection->update('users', $data, ['id' => $id]);
    }

    public function updateLastLogin(int $id): bool
    {
        return (bool) $this->connection->update(
            'users',
            ['last_login' => new DateTime('now')],
            ['id' => $id],
            ['last_login' => 'datetime']
        );
    }
}

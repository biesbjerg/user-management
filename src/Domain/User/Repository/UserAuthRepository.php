<?php
declare(strict_types=1);

namespace App\Domain\User\Repository;

use Cake\Database\Connection;

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
                'password'
            ])
            ->where([
                'username' => $username,
                'is_enabled' => 1
            ]);

        $row = $query->execute()->fetch('assoc');

        return $row ?: [];
    }
}

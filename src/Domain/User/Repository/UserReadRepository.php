<?php
declare(strict_types=1);

namespace App\Domain\User\Repository;

use Cake\Database\Connection;

class UserReadRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAll(): array
    {
        $query = $this->connection->newQuery()
            ->from('users')
            ->select([
                'id',
                'username',
                'name',
                'is_enabled',
                'last_login',
                'created'
            ]);

        $rows = $query->execute()->fetchAll('assoc');

        return $rows ?: [];
    }

    public function findById(int $id): array
    {
        $query = $this->connection->newQuery()
            ->from('users')
            ->select([
                'id',
                'username',
                'name',
                'is_enabled',
                'last_login',
                'created'
            ])
            ->where(['id' => $id]);

        $row = $query->execute()->fetch('assoc');

        return $row ?: [];
    }
}

<?php
declare(strict_types=1);

namespace App\Domain\User\Repository;

use Cake\Database\Connection;

class UserCreateRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function create(array $data): ?string
    {
        return $this->connection->insert('users', $data)->lastInsertId();
    }
}

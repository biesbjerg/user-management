<?php
declare(strict_types=1);

namespace App\Domain\User\Repository;

use Cake\Database\Connection;

class UserUpdateRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function update($id, array $data): bool
    {
        return (bool) $this->connection->update('users', $data, ['id' => $id]);
    }
}

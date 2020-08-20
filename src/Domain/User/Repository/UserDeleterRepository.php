<?php
declare(strict_types=1);

namespace App\Domain\User\Repository;

use Cake\Database\Connection;

class UserDeleterRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function delete($id): bool
    {
        $query = $this->connection->delete('users', ['id' => $id]);

        return $query->execute();
    }
}

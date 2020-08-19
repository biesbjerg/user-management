<?php
declare(strict_types=1);

namespace App\Domain\User\Repository;

use Cake\Database\Connection;

class UserReaderRepository
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
            ->select('*');

        $rows = $query->execute()->fetchAll('assoc');

        return $rows ?: [];
    }

    public function findById($id): array
    {
        $query = $this->connection->newQuery()
            ->from('users')
            ->select('*')
            ->where(['id' => $id]);

        $row = $query->execute()->fetch('assoc');

        return $row ?: [];
    }

    /*public function create(array $data): bool
    {
        return $this->connection
            ->insert('users', $data)
            ->execute();
    }

    public function update($id, array $data): bool
    {
        return $this->connection
            ->update('users', $data, ['id' => $id])
            ->execute();
    }

    public function delete($id): bool
    {
        return $this->connection
            ->delete('users', ['id' => $id])
            ->execute();
    }*/
}

<?php
declare(strict_types=1);

namespace App\Datasource\User;

use App\Datasource\User\UserRepositoryInterface;
use Cake\Database\Connection;
use DateTime;

class UserRepository implements UserRepositoryInterface
{
    private string $table = 'users';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function find(int $id, ?array $fields = []): array
    {
        $query = $this->connection->newQuery()
            ->from($this->table)
            ->select('*')
            ->where(['id' => $id]);

        if ($fields) {
            $query->select($fields, true);
        }

        $row = $query->execute()->fetch('assoc');

        return $row ?: [];
    }

    public function findByUsername(string $username, ?array $fields = []): array
    {
        $query = $this->connection->newQuery()
            ->from($this->table)
            ->select('*')
            ->where(['username' => $username]);

        if ($fields) {
            $query->select($fields, true);
        }

        $row = $query->execute()->fetch('assoc');

        return $row ?: [];
    }

    public function findAll(?array $conditions = [], ?array $fields = null, ?string $order = null): array
    {
        $query = $this->connection->newQuery()
            ->from($this->table)
            ->select('*')
            ->where($conditions);

        if ($fields) {
            $query->select($fields, true);
        }
        if ($order) {
            $query->order($order);
        }

        $rows = $query->execute()->fetchAll('assoc');

        return $rows ?: [];
    }

    public function create(array $data): int
    {
        return (int) $this->connection->insert($this->table, $data)->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->connection->update($this->table, $data, ['id' => $id]);
    }

    public function updateLastLogin(int $id): bool
    {
        return (bool) $this->connection->update(
            $this->table,
            ['last_login' => new DateTime('now')],
            ['id' => $id],
            ['last_login' => 'datetime']
        );
    }

    public function delete(int $id): bool
    {
        return (bool) $this->connection->delete($this->table, ['id' => $id]);
    }
}

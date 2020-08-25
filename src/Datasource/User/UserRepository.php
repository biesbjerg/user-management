<?php
declare(strict_types=1);

namespace App\Datasource\User;

use App\Datasource\RecordInterface;
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

    public function find(int $id, ?array $fields = []): ?UserRecord
    {
        $query = $this->connection->newQuery()
            ->from($this->table)
            ->select('*')
            ->where(['id' => $id]);

        if ($fields) {
            $query->select($fields, true);
        }

        $row = $query->execute()->fetch('assoc');
        if (!$row) {
            return null;
        }

        return new UserRecord($row);
    }

    public function findByUsername(string $username, ?array $fields = []): ?UserRecord
    {
        $query = $this->connection->newQuery()
            ->from($this->table)
            ->select('*')
            ->where(['username' => $username]);

        if ($fields) {
            $query->select($fields, true);
        }

        $row = $query->execute()->fetch('assoc');
        if (!$row) {
            return null;
        }

        return new UserRecord($row);
    }

    public function isUsernameTaken(string $username, ?array $conditions = []): bool
    {
        $query = $this->connection->newQuery()
            ->from($this->table)
            ->select('id')
            ->where(['username' => $username]);

        if ($conditions) {
            $query->andWhere($conditions);
        }

        $row = $query->execute()->fetch('assoc');

        return (bool) $row;
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
        if (!$rows) {
            return [];
        }

        return array_map(fn($row) => new UserRecord($row), $rows);
    }

    public function create(RecordInterface $user): int
    {
        return (int) $this->connection->insert($this->table, $user->getData())->lastInsertId();
    }

    public function update(int $id, RecordInterface $record): bool
    {
        // Allow update without changing password.
        // TODO: This feels hacky, find better way
        $data = $record->getData();
        if ($data['password'] === '' || $data['password'] === null) {
            unset($data['password']);
        }
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

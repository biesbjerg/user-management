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

    public function find($id, ?array $fields = []): ?array
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

        return $row;
    }

    public function findByUsername(string $username, ?array $fields = []): ?array
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

        return $row;
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

        return $rows;
    }

    public function create(RecordInterface $user)
    {
        return $this->connection->insert($this->table, $user->getData())->lastInsertId();
    }

    public function update($id, RecordInterface $record): bool
    {
        // Allow update without changing password.
        // TODO: This feels hacky, find better way
        $data = $record->getData();
        if ($data['password'] === '' || $data['password'] === null) {
            unset($data['password']);
        }
        return (bool) $this->connection->update($this->table, $data, ['id' => $id]);
    }

    public function updateLastLogin($id): bool
    {
        return (bool) $this->connection->update(
            $this->table,
            ['last_login' => new DateTime('now')],
            ['id' => $id],
            ['last_login' => 'datetime']
        );
    }

    public function updatePassword($id, string $hash): bool
    {
        return (bool) $this->connection->update(
            $this->table,
            ['password' => $hash],
            ['id' => $id]
        );
    }

    public function delete($id): bool
    {
        return (bool) $this->connection->delete($this->table, ['id' => $id]);
    }
}

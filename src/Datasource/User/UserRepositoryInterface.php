<?php
declare(strict_types=1);

namespace App\Datasource\User;

use App\Datasource\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByUsername(string $username, ?array $fields = []): ?UserRecord;

    public function isUsernameTaken(string $username, ?array $conditions = []): bool;

    public function updateLastLogin(int $id): bool;
}

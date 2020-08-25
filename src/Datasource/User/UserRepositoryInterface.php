<?php
declare(strict_types=1);

namespace App\Datasource\User;

use App\Datasource\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByUsername(string $username, ?array $fields = []): array;

    public function updateLastLogin(int $id): bool;
}

<?php
declare(strict_types=1);

namespace App\Datasource;

interface RepositoryInterface
{
    public function find(int $id, ?array $fields = []): array;

    public function findAll(?array $conditions = [], ?array $fields = [], ?string $order = null): array;

    public function create(array $data): int;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}

<?php
declare(strict_types=1);

namespace App\Datasource;

use App\Datasource\RecordInterface;

interface RepositoryInterface
{
    public function find(int $id, ?array $fields = []): ?RecordInterface;

    public function findAll(?array $conditions = [], ?array $fields = [], ?string $order = null): array;

    public function create(RecordInterface $data): int;

    public function update(int $id, RecordInterface $data): bool;

    public function delete(int $id): bool;
}

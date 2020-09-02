<?php
declare(strict_types=1);

namespace App\Datasource;

interface RepositoryInterface
{
    /**
     * Undocumented function
     *
     * @param string|int $id
     * @param string[]|null $fields
     * @return array|null
     */
    public function find($id, ?array $fields = []): ?array;

    /**
     * Undocumented function
     *
     * @param array|null $conditions
     * @param string[]|null $fields
     * @param string|null $order
     * @return array
     */
    public function findAll(?array $conditions = [], ?array $fields = [], ?string $order = null): array;

    /**
     * Undocumented function
     *
     * @param array $data
     * @return string|int
     */
    public function create(array $data);

    /**
     * Undocumented function
     *
     * @param string|int $id
     * @param array $data
     * @return boolean
     */
    public function update($id, array $data): bool;

    /**
     * Undocumented function
     *
     * @param string|int $id
     * @return boolean
     */
    public function delete($id): bool;
}

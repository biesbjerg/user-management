<?php
declare(strict_types=1);

namespace App\Datasource\User;

use App\Datasource\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Undocumented function
     *
     * @param string $username
     * @param string[]|null $fields
     * @return array|null
     */
    public function findByUsername(string $username, ?array $fields = []): ?array;

    /**
     * Undocumented function
     *
     * @param string $username
     * @param array|null $conditions
     * @return boolean
     */
    public function isUsernameTaken(string $username, ?array $conditions = []): bool;

    /**
     * Undocumented function
     *
     * @param string|int $id
     * @return boolean
     */
    public function updateLastLogin($id): bool;

    /**
     * Undocumented function
     *
     * @param string|int $id
     * @param string $hash
     * @return boolean
     */
    public function updatePassword($id, string $hash): bool;
}

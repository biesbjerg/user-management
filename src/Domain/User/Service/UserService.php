<?php
declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Datasource\User\UserRepository;

class UserService
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetchAllUsers(): array
    {
        return $this->repository->findAll(null, [
            'id',
            'name',
            'username',
            'is_enabled',
            'last_login'
        ]);
    }

    public function fetchUser(int $id): array
    {
        return $this->repository->find($id, [
            'id',
            'name',
            'username',
            'is_enabled',
            'last_login',
            'created'
        ]);
    }

    public function create(array $data): int
    {
        if (array_key_exists('password', $data) && ($data['password'] !== '' || $data['password'] !== null)) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // TODO: Validate data

        return $this->repository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        // Allow user update without changing password
        if (array_key_exists('password', $data) && ($data['password'] === '' || $data['password'] === null)) {
            unset($data['password']);
        } else {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // TODO: Validate data

        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

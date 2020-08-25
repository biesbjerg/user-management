<?php
declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Datasource\User\UserRecord;
use App\Datasource\User\UserRepository;
use App\Domain\User\Validation\UserValidator;
use App\Exception\ValidationException;

class UserService
{
    private UserRepository $repository;

    private UserValidator $validator;

    public function __construct(UserRepository $repository, UserValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
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

    public function fetchUser(int $id): ?UserRecord
    {
        return $this->repository->find($id, [
            'id',
            'name',
            'username',
            'is_enabled',
            'last_login'
        ]);
    }

    public function create(array $data): int
    {
        $user = new UserRecord($data);

        $validates = $this->validator->check($user, 'create');
        if (!$validates) {
             throw new ValidationException('Unable to create user', $this->validator);
        }

        if ($user->password !== null && $user->password !== '') {
            $user->password = password_hash($user->password, PASSWORD_DEFAULT);
        }

        return $this->repository->create($user);
    }

    public function update(int $id, array $data): bool
    {
        $user = new UserRecord($data);

        $validates = $this->validator->check($user, 'update');
        if (!$validates) {
            throw new ValidationException('Unable to update user', $this->validator);
        }

        // Hash password if present
        if ($user->password !== '' && $user->password !== null) {
            $user->password = password_hash($user->password, PASSWORD_DEFAULT);
        }

        return $this->repository->update($id, $user);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

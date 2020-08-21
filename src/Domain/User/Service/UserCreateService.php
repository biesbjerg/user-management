<?php
declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserCreateRepository;

class UserCreateService
{
    private UserCreateRepository $repository;

    public function __construct(UserCreateRepository $repository)
    {
        $this->repository = $repository;
    }

    public function save(array $data): ?string
    {
        // TODO: Remove
        unset($data['_METHOD']);

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // TODO: Validate data

        return $this->repository->create($data);
    }
}

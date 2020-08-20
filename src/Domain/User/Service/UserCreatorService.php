<?php
declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserCreatorRepository;

class UserCreatorService
{
    private UserCreatorRepository $repository;

    public function __construct(UserCreatorRepository $repository)
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

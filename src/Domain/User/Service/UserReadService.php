<?php
declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserReadRepository;

class UserReadService
{
    private UserReadRepository $repository;

    public function __construct(UserReadRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(int $id): array
    {
        return $this->repository->findById($id);
    }
}

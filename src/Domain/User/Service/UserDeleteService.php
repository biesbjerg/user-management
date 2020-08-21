<?php
declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserDeleteRepository;

class UserDeleteService
{
    private UserDeleteRepository $repository;

    public function __construct(UserDeleteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function delete($id): bool
    {
        return $this->repository->delete($id);
    }
}

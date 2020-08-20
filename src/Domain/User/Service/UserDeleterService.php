<?php
declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserDeleterRepository;

class UserDeleterService
{
    private UserDeleterRepository $repository;

    public function __construct(UserDeleterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function delete($id): bool
    {
        return $this->repository->delete($id);
    }
}

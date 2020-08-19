<?php
declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserReaderRepository;

class UserReaderService
{
    private UserReaderRepository $repository;

    public function __construct(UserReaderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllUsers(): array
    {
        return $this->repository->findAll();
    }

    public function getUser($id): array
    {
        return $this->repository->findById($id);
    }

/*    public function create(array $data): bool
    {
        // TODO: Validate data
        return $this->userRepository->create($data);
    }

    public function update($id, array $data): bool
    {
        // TODO: Validate data
        return $this->userRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->userRepository->delete($id);
    }*/
}

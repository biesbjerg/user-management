<?php
declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserUpdaterRepository;

class UserUpdaterService
{
    private UserUpdaterRepository $repository;

    public function __construct(UserUpdaterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function save($id, array $data): bool
    {
        // TODO: Remove
        unset($data['_METHOD']);

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // TODO: Validate data

        return $this->repository->update($id, $data);
    }
}

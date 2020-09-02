<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Datasource\User\UserRecord;
use App\Datasource\User\UserRecordFactory;
use App\Datasource\User\UserRepository;
use App\Validation\User\UserValidator;
use App\Exception\ValidationException;

class UserService
{
    private UserRepository $userRepository;

    private UserRecordFactory $userFactory;

    private UserValidator $userValidator;

    private PasswordService $passwordService;

    public function __construct(
        UserRepository $userRepository,
        UserRecordFactory $userFactory,
        UserValidator $userValidator,
        PasswordService $passwordService
    ) {
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->userValidator = $userValidator;
        $this->passwordService = $passwordService;
    }

    /**
     * Undocumented function
     *
     * @return UserRecord[]
     */
    public function fetchAllUsers(): array
    {
        $rows = $this->userRepository->findAll([], [
            'id',
            'name',
            'username',
            'is_enabled',
            'last_login'
        ]);
        if (!$rows) {
            return [];
        }

        return $this->userFactory->newRecordSet($rows);
    }

    /**
     * Undocumented function
     *
     * @param string|int $id
     * @return UserRecord|null
     */
    public function fetchUser($id): ?UserRecord
    {
        $row = $this->userRepository->find($id, [
            'id',
            'name',
            'username',
            'is_enabled',
            'last_login'
        ]);
        if (!$row) {
            return null;
        }

        return $this->userFactory->newRecord($row);
    }

    /**
     * Undocumented function
     *
     * @param array $data
     * @return string|int
     */
    public function create(array $data)
    {
        $user = $this->userFactory->newRecord($data);

        $validates = $this->userValidator->check($user, 'create');
        if (!$validates) {
             throw new ValidationException('Unable to create user', $this->userValidator);
        }

        if ($user->password !== null && $user->password !== '') {
            $user->password = $this->passwordService->hash($user->password);
        }

        return $this->userRepository->create($user);
    }

    /**
     * Undocumented function
     *
     * @param string|int $id
     * @param array $data
     * @return boolean
     */
    public function update($id, array $data): bool
    {
        $user = $this->userFactory->newRecord($data);

        $validates = $this->userValidator->check($user, 'update');
        if (!$validates) {
            throw new ValidationException('Unable to update user', $this->userValidator);
        }

        // Hash password if present
        if ($user->password !== null && $user->password !== '') {
            $user->password = $this->passwordService->hash($user->password);
        }

        return $this->userRepository->update($id, $user);
    }

    /**
     * Undocumented function
     *
     * @param string|int $id
     * @return boolean
     */
    public function delete($id): bool
    {
        return $this->userRepository->delete($id);
    }
}

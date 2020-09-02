<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Datasource\User\UserRecord;
use App\Datasource\User\UserRecordFactory;
use App\Datasource\User\UserRepository;
use App\Validation\User\UserValidator;
use App\Exception\ValidationException;
use App\Util\FiltersDataTrait;

class UserService
{
    use FiltersDataTrait;

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
        $filteredData = $this->filterData($data, [
            'name',
            'username',
            'password',
            'is_enabled'
        ]);

        $isValid = $this->userValidator->check($filteredData, 'create');
        if (!$isValid) {
             throw new ValidationException('Unable to create user', $this->userValidator);
        }

        if (array_key_exists('password', $filteredData) && $filteredData['password'] !== '') {
            $filteredData['password'] = $this->passwordService->hash($filteredData['password']);
        }

        return $this->userRepository->create($filteredData);
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
        $filteredData = $this->filterData($data, [
            'id',
            'name',
            'username',
            'password',
            'is_enabled'
        ]);

        $isValid = $this->userValidator->check($filteredData, 'update');
        if (!$isValid) {
            throw new ValidationException('Unable to update user', $this->userValidator);
        }

        if (array_key_exists('password', $filteredData)) {
            if ($filteredData['password'] !== '') {
                $filteredData['password'] = $this->passwordService->hash($filteredData['password']);
            } else {
                // Allow update without changing password.
                unset($filteredData['password']);
            }
        }

        return $this->userRepository->update($id, $filteredData);
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

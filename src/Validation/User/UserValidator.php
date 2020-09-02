<?php
declare(strict_types=1);

namespace App\Validation\User;

use App\Datasource\User\UserRepository;
use App\Validation\AbstractValidator as Validator;

class UserValidator extends Validator
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    protected function validateCreate(array $data): void
    {
        if (!array_key_exists('name', $data) || $data['name'] === '') {
            $this->messages['name'] = 'Name cannot be empty';
        }

        if (!array_key_exists('username', $data) || $data['username'] === '') {
            $this->messages['username'] = 'Username cannot be empty';
        } else {
            $isTaken = $this->userRepository->isUsernameTaken($data['username']);
            if ($isTaken) {
                $this->messages['username'] = 'Username is taken';
            }
        }

        if (!array_key_exists('password', $data) || $data['password'] === '') {
            $this->messages['password'] = 'Password cannot be empty';
        }
    }

    protected function validateUpdate(array $data): void
    {
        if (!array_key_exists('id', $data) || $data['id'] === '') {
            $this->messages['id'] = 'ID cannot be empty';
        }

        if (!array_key_exists('name', $data) || $data['name'] === '') {
            $this->messages['name'] = 'Name cannot be empty';
        }

        if (!array_key_exists('username', $data) || $data['username'] === '') {
            $this->messages['username'] = 'Username cannot be empty';
        } else {
            $isTaken = $this->userRepository->isUsernameTaken($data['username'], [
                'id IS NOT' => $data['id']
            ]);
            if ($isTaken) {
                $this->messages['username'] = 'Username is taken';
            }
        }
    }
}

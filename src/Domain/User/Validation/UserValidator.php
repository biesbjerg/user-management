<?php
declare(strict_types=1);

namespace App\Domain\User\Validation;

use App\Datasource\User\UserRecord;
use App\Datasource\User\UserRepository;
use App\Validation\AbstractValidator as Validator;

class UserValidator extends Validator
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function validateCreate(UserRecord $user): void
    {
        if (empty($user->name)) {
            $this->messages['name'] = 'Name cannot be empty';
        }

        if (empty($user->username)) {
            $this->messages['username'] = 'Username cannot be empty';
        } else {
            $isTaken = $this->repository->isUsernameTaken($user->username);
            if ($isTaken) {
                $this->messages['username'] = 'Username is taken';
            }
        }

        if (empty($user->password)) {
            $this->messages['password'] = 'Password cannot be empty';
        }
    }

    protected function validateUpdate(UserRecord $user): void
    {
        if (empty($user->id)) {
            $this->messages['id'] = 'ID cannot be empty';
        }

        if (empty($user->name)) {
            $this->messages['name'] = 'Name cannot be empty';
        }

        if (empty($user->username)) {
            $this->messages['username'] = 'Username cannot be empty';
        } else {
            $isTaken = $this->repository->isUsernameTaken($user->username, [
                'id IS NOT' => $user->id
            ]);
            if ($isTaken) {
                $this->messages['username'] = 'Username is taken';
            }
        }
    }
}

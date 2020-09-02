<?php
declare(strict_types=1);

namespace App\Datasource\User;

use App\Datasource\AbstractRecord as Record;

class UserRecord extends Record
{
    public ?string $id;
    public ?string $name;
    public ?string $username;
    public ?string $password;
    public ?string $is_enabled;
    public ?string $last_login;
}

<?php
declare(strict_types=1);

namespace App\Datasource\User;

use App\Datasource\AbstractRecord as Record;

class UserRecord extends Record
{
    public $id;
    public $name;
    public $username;
    public $password;
    public $is_enabled;
    public $last_login;
}

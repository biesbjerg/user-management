<?php
declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Kim Biesbjerg',
                'username' => 'biesbjerg',
                'password' => password_hash('1234', PASSWORD_DEFAULT),
                'is_enabled' => 1
            ],
            [
                'name' => 'Ordbogen',
                'username' => 'ordbogen',
                'password' => password_hash('1234', PASSWORD_DEFAULT),
                'is_enabled' => 1
            ]
        ];

        $users = $this->table('users');
        $users->truncate();
        $users->insert($data)->saveData();
    }
}

<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('users', ['signed' => false]);
        $table
            ->addColumn('name', 'string', ['limit' => 50, 'null' => false])
            ->addColumn('username', 'string', ['limit' => 50, 'null' => false])
            ->addColumn('password', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('is_enabled', 'boolean', ['signed' => false, 'default' => true])
            ->addColumn('last_login', 'datetime', ['null' => true])
            ->addIndex(['username'], ['unique' => true])
            ->create();
    }
}

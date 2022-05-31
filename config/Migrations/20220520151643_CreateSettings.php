<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateSettings extends AbstractMigration
{

    public $autoId = false;
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $settings_table = $this->table('settings');

        $settings_table->addColumn('id_settings', 'integer', [
           
            'limit' => 50
        ])
                ->addPrimaryKey('id_settings')
                ->addColumn('username_settings', 'string' , ['limit' => 50])
                ->addColumn('notif_comm', 'string' , ['limit' => 3, 'default' => 'oui'])
                ->addColumn('notif_follow', 'string' , ['limit' => 3, 'default' => 'oui'])
                ->addForeignKey('username_settings', 'users', 'username', ['delete'=> 'CASCADE', 'constraint' => 'fk_settings_users_username'])
                ->create();
    }
}
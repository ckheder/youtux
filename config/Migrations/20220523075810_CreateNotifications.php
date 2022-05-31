<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateNotifications extends AbstractMigration
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
        $notifications_table = $this->table('notifications');

        $notifications_table->addColumn('id_notification', 'integer', [
            'autoIncrement' => true,
            'limit' => 50
        ])
                ->addPrimaryKey('id_notification')
                ->addColumn('user_notification', 'string' , ['limit' => 50])
                ->addColumn('notification_content', 'text')
                ->addColumn('created', 'datetime')
                ->addForeignKey('user_notification', 'users', 'username', ['delete'=> 'CASCADE', 'constraint' => 'fk_notifications_users_username'])
                ->create();
    }

}


<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateFollows extends AbstractMigration
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
        $follows = $this->table('follows');

        $follows->addColumn('id_follow', 'integer', [
           
            'limit' => 50
        ])
                ->addPrimaryKey('id_follow')
                ->addColumn('follower', 'string' , ['limit' => 50])
                ->addColumn('following', 'string' , ['limit' => 50])
                ->addForeignKey('follower', 'users', 'username', ['delete'=> 'CASCADE', 'constraint' => 'fk_follower_users_username'])
                ->addForeignKey('following', 'users', 'username', ['delete'=> 'CASCADE', 'constraint' => 'fk_following_users_username'])
                ->create();

    }
}

<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateCommunityPosts extends AbstractMigration
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
        $community_table = $this->table('community_posts');

        $community_table->addColumn('id_community_post', 'integer', [
           
            'limit' => 50
        ])
                ->addPrimaryKey('id_community_post')
                ->addColumn('username_community_post', 'string' , ['limit' => 50])
                ->addColumn('message_community_post', 'text')
                ->addColumn('nb_comm', 'integer', ['limit' => 111, 'default' => 0])
                ->addColumn('created', 'datetime')
                ->addColumn('modified', 'datetime')
                ->addForeignKey('username_community_post', 'users', 'username', ['delete'=> 'CASCADE', 'constraint' => 'fk_community_posts_users_username'])
                ->create();
    }
}

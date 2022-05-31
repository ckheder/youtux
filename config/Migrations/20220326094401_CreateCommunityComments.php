<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateCommunityComments extends AbstractMigration
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

        $communitycomment_table = $this->table('community_comments');

        $communitycomment_table->addColumn('id_community_comment', 'integer', [
           
            'limit' => 50
        ])
                ->addPrimaryKey('id_community_comment')
                ->addColumn('username_community_comment', 'string' , ['limit' => 50])
                ->addColumn('community_comment', 'text')
                ->addColumn('idmessage_community', 'integer', ['limit' => 50])
                ->addColumn('created', 'datetime')
                ->addColumn('modified', 'datetime')
                ->addForeignKey('username_community_comment', 'users', 'username', ['delete'=> 'CASCADE', 'constraint' => 'fk_community_comments_users_username'])
                ->addForeignKey('idmessage_community', 'community_posts', 'id_community_post', ['delete'=> 'CASCADE', 'constraint' => 'fk_community_comment_posts_id'])
                ->create();
    }
}

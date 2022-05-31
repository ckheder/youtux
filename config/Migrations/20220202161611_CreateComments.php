<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateComments extends AbstractMigration
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
        $comments = $this->table('comments');

        $comments->addColumn('id_comm', 'integer', [
           
            'limit' => 50
        ])
                ->addPrimaryKey('id_comm')
                ->addColumn('commentaire', 'text')
                ->addColumn('id_movie', 'integer' , ['limit' => 11])
                ->addColumn('user_comm', 'string' , ['limit' => 50])
                ->addColumn('created', 'datetime')
                ->addColumn('modified', 'datetime')
                ->addForeignKey('id_movie', 'movies', 'id_movie', ['delete'=> 'CASCADE', 'constraint' => 'fk_movies_id'])
                ->addForeignKey('user_comm', 'users', 'username', ['delete'=> 'CASCADE', 'constraint' => 'fk_comments_users_username'])
                ->create();
    }
}

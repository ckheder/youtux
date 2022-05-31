<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateFavoriteMovies extends AbstractMigration
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
        $favoritemovies = $this->table('favorite_movies');
        $favoritemovies->addColumn('id_favorite_movies', 'integer', [
           
            'limit' => 50
        ])
                ->addPrimaryKey('id_favorite_movies')
                ->addColumn('username_favorite_movies', 'string' , ['limit' => 50])
                ->addColumn('favorite_movies', 'integer', ['limit' => 11])
                ->addForeignKey('username_favorite_movies', 'users', 'username', ['delete'=> 'CASCADE', 'constraint' => 'fk_favorite_movies_users_username'])
                ->addForeignKey('favorite_movies', 'movies', 'id_movie', ['delete'=> 'CASCADE', 'constraint' => 'fk_favorite_movies_id'])
                ->create();
    }
}

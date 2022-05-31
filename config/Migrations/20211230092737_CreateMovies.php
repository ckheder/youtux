<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateMovies extends AbstractMigration
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
        $movies = $this->table('movies');
        $movies->addColumn('id_movie', 'integer', [
           
            'limit' => 50
        ])
                ->addPrimaryKey('id_movie')
                ->addColumn('titre', 'string', ['limit' => 50])
                ->addColumn('filename', 'text')
                ->addColumn('description', 'text')
                ->addColumn('auteur', 'string', ['limit' => 50])
                ->addColumn('categorie', 'string', ['limit' => 50])
                ->addColumn('nb_like', 'integer' , ['limit' => 111])
                ->addColumn('nb_comment', 'integer' , ['limit' => 111])
                ->addColumn('nb_vues', 'integer' , ['limit' => 111])
                ->addColumn('type', 'string')
                ->addColumn('allow_comment','boolean')
                ->addColumn('created', 'datetime')
                ->addIndex(['titre','description','auteur'], ['type' => 'fulltext','name' => 'ft_movies'])
                ->addForeignKey('auteur', 'users', 'username', ['delete'=> 'CASCADE', 'constraint' => 'fk_users_username'])
                ->create();


                if ($this->isMigratingUp()) {



                    $rows = [

                        [
                            'id_movie' => 6135,
                            'titre'    => 'videotestmp4',
                            'filename'    => 'test.mp4',
                            'description'    => 'test de vidéo au format mp4',
                            'auteur'    => 'christophe_kheder',
                            'categorie'    => 'Mode',
                            'nb_like'    => 0,
                            'nb_comment'    => 0,
                            'nb_vues'    => 0,
                            'type'    => 'public',
                            'allow_comment' => '0',
                            'created'  => '2022-01-21 16:59:39'
                        ]
                    ];
            
                    $movies->insert($rows)->save();
                }
    }
}

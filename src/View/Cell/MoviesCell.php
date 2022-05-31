<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Movies cell
 */
class MoviesCell extends Cell
{
    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Initialization logic run at the end of object construction.
     *
     * @return void
     */
    public function initialize(): void
    {
    }

    /**
     * Méthode CountMovies
     *
     * Calcul du nombre de vidéo posté par un utilisateur donné en paramètre
     *
     * Sortie : $countmovies -> nombre de vidéo posté par un utilisateur donné en paramètre
     *
     *
    */

        public function countmovies($username)
    {
        $countmovies = $this->fetchTable('Movies')->find()
                                                    ->where(['auteur' => $username])
                                                    ->count();

        $this->set('countmovies', $countmovies);
    }

    /**
     * Méthode CheckFavoriteMovie
     *
     * Test si la vidéo en cours de consultation fait partie de mes favoris ou non
     *
     * Sortie : $countmovies -> nombre de vidéo posté par un utilisateur donné en paramètre
     *
     *
    */

    public function checkfavoritemovie($authname, $idmovie)
    {
        $checkfavorite = $this->fetchTable('FavoriteMovies')->find()
                                        ->select(['id_favorite_movies'])
                                        ->where(['username_favorite_movies' => $authname,'favorite_movies' => $idmovie]);

                                        $countfavorite = $checkfavorite->count(); // récupération du nombre de résultat
                                        

                if($countfavorite == 0) // pas de favori existant : préparation de la variable contenant le résultat
            { 
                $favoritestatus = 'nofavorite';
            }
            else
        {

                foreach ($checkfavorite as $checkfavorite) // favori existant
            {
                $idfavoritemovie = $checkfavorite['id_favorite_movies']; // identifiant en bdd du favori

                $favoritestatus = 'favorite';

                $this->set('idfavoritemovie',$idfavoritemovie); // renvoi de cet identifiant au Javascript
            }

        }

            $this->set('favoritestatus', $favoritestatus); // renvoi de la variable contenant le résultat du test

            $this->set('idmovie',$idmovie); // renvoi de l'identifiant de la vidéo
    }

}

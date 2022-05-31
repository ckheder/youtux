<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Follow cell
 */
class FollowCell extends Cell
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
     * Méthode followstatus : on vérifie si un abonnement existe déjà entre les deux utilisateurs entrés en paramètres
     *
     * Paramètres : $authname -> nom de la personne actuellement connectée, $username -> nom du profil que je visite ou résultat de recherche ou abonnés
     *
     * On récupère l'état actuel de l'abonnement
     */
        public function followstatus($authname, $username)
    {
        $checkfollow = $this->fetchTable('Follows')->find()
                                        ->select()
                                        ->where(['follower' => $authname,'following' => $username])
                                        ->count();

            if($checkfollow == 0) // pas d'abonnement existant
        {
            $followstatus = 'nofollow';
        }
            else
        {
            $followstatus = 'follow';
        }

            $this->set('followstatus', $followstatus);

            $this->set('username',$username);

    }

    /**
     * Méthode CountFollowers
     *
     * Calcul du nombre d'abonnés du profil donné en paramètres
     *
     * Sortie : $countfollowers -> nombre de follower du profil donné
     *
     *
    */

        public function countfollowers($username)
    {
        $countfollowers = $this->fetchTable('Follows')->find()
                                                        ->where(['following' => $username])
                                                        ->count();

        $this->set('countfollowers', $countfollowers);
    }
}

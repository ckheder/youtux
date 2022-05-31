<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Block cell
 */
class BlockCell extends Cell
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
     * Méthode blockstatus : on vérifie si un blocage existe déjà entre les deux utilisateurs entrés en paramètres
     *
     * Paramètres : $authname -> nom de la personne actuellement connectée, $username -> nom du profil que je visite
     *
     * On récupère l'état actuel du blocage
     */
        public function blockstatus($authname, $username)
    {
        $checkblock = $this->fetchTable('Block')->find()
                                        ->select()
                                        ->where(['bloqueur' => $authname,'bloque' => $username])
                                        ->count();

            if($checkblock == 0) // pas de blocage existant
        {
            $blockstatus = 'noblock';
        }
            else
        {
            $blockstatus = 'block';
        }

            $this->set('blockstatus', $blockstatus);

            $this->set('username',$username);

    }
}

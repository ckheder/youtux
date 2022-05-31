<?php
namespace App\Event;


use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

/**
 * Listener UserListener
 *
 * Création de la ligne Settings d'un nouvel enregistré ainsi que les différends dossiers utilisateurs, avatar par défaut
 *
 */

class UsersListener implements EventListenerInterface {

        public function implementedEvents(): array

    {
        return [
            'Model.Users.afteradd' => 'addentity',
        ];
    }

/**
     * Méthode addentity
     *
     * Création de la ligne Settings pour un utilisateur nouvellement enregistré, création du dossier utilisateur, avatar par défaut
     *
     * Paramètres : $user -> tableau contenant le nom de la persone qui vient de s'inscrire
     *
*/

            public function addentity($event, $user)
        {

            $entity = TableRegistry::get('Settings');

            $query = $entity->query();

            // le reste est complété par le SGBD

            $query->insert(['id_settings','username_settings'])
                    ->values([
                                'id_settings' => $user->id,
                                'username_settings' => $user->username
                            ])

                    ->execute();


                //creation du dossier utilisateur

                $dir = new Folder('/var/www/html/youtux/webroot/users/'.$user->username.'', true, 0755);

                // copie de l'avatar par defaut

                $srcfile='/var/www/html/youtux/webroot/img/default.png';

                $dstfile='/var/www/html/youtux/webroot/users/'.$user->username.'/'.$user->username.'.jpg';

                copy($srcfile, $dstfile);


        }

}

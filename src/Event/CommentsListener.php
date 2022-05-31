<?php
namespace App\Event;

use Cake\I18n\Time;
use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

/**
 * Comments listener
 *
 * Création d'une notification indiquant qu'un utilisateur à commenté un post
 */

class CommentsListener implements EventListenerInterface {

        public function implementedEvents(): array

    {
        return [
            'Model.Comments.afteradd' => 'notifcomm',
        ];
    }

/**
     * Méthode notifcomm
     *
     * Création d'une notification de nouveau commentaire
     *
     * Paramètres : $data -> tableau contenant les informations du commentaire, $authormoviet -> personne à qui est destiné la notification
     *
*/

            public function notifcomm($event, $data, $authormovie)
        {

          $entity = TableRegistry::get('Notifications');

          $notif = '<img src="/youtux/users/'.$data['user_comm'].'/'.$data['user_comm'].'.jpg" alt="image utilisateur" width="32" height="32" class="d-inline rounded-circle me-1"/><a href="/youtux/'.$data['user_comm'].'" class="text-decoration-none">'.$data['user_comm'].'</a> à commenté votre <a href="/youtux/v/'.$data['id_movie'].'" class="text-decoration-none">vidéo.</a>';

          $notif_comm = $entity->newEmptyEntity();

          $notif_comm->user_notification = $authormovie; // auteur de la vidéo

          $notif_comm->notification_content = $notif; // notification

          $notif_comm->created =  Time::now(); // date actuelle

          $entity->save($notif_comm); // création de l'entité

        }

}

<?php
namespace App\Event;

use Cake\I18n\Time;
use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

/**
 * Follows listener
 *
 * Création d'une notification indiquant qu'un utilisateur à commenté un post
 */

class FollowsListener implements EventListenerInterface {

        public function implementedEvents(): array

    {
        return [
            'Model.Follows.afteradd' => 'notiffollow',
        ];
    }

/**
     * Méthode notiffollow
     *
     * Création d'une notification de nouvel abonnement
     *
     * Paramètres : $newfollow -> tableau contenant les informations du nouvel abonné ainsi que le destinataire de la notification
     *
*/

            public function notiffollow($event, $newfollow)
        {

          $entity = TableRegistry::get('Notifications');

          $notif = '<img src="/youtux/users/'.$newfollow['follower'].'/'.$newfollow['follower'].'.jpg" alt="image utilisateur" width="32" height="32" class="d-inline rounded-circle me-1"/><a href="/youtux/'.$newfollow['follower'].'" class="text-decoration-none">'.$newfollow['follower'].'</a> s\'est abonné à votre chaîne.</a>';

          $notif_follow = $entity->newEmptyEntity();

          $notif_follow->user_notification = $newfollow['following']; // destinataire notification

          $notif_follow->notification_content = $notif; // notification

          $notif_follow->created =  Time::now(); // date actuelle

          $entity->save($notif_follow); // création entité


        }

}
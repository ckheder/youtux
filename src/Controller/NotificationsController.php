<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Notifications Controller
 *
 * @method \App\Model\Entity\Notification[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NotificationsController extends AppController
{
    /**
     * Index method
     * 
     * Affichage des notifications par order de date décroissant
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $notifications = $this->paginate($this->Notifications->find()->where(['user_notification' =>$this->Authentication->getIdentity()->username ])
                                                                    ->order(['created' => 'DESC']));

        $this->set('title' , 'Notifications | Youtux'); // titre de la page

        $this->set(compact('notifications'));
    }


    /**
     * Delete method
     * 
     * Suppression d'une notification
     *
     * @param string|null $id Notification id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if($this->request->is('ajax')) // requête AJAX
      {

        $entity_notification = $this->Notifications->get($this->request->input()); // récupération de l'entité 'notification' correspondante

        // su je ne suis pas le propriétaire de cette notification, renvoi d'une réponse

            if($entity_notification->user_notification != $this->Authentication->getIdentity()->username)
        {

          return $this->response ->withStringBody('notownernotif');
                                  
        }

        // notification supprimée avec succès , renvoi d'une réponse au format TEXT

            elseif ($this->Notifications->delete($entity_notification))
        {

            return $this->response->withStringBody('deletenotifok');
             
        }
            else  // échec suppression de notification, renvoi d'une réponse au format TEXT
        {

                return $this->response->withStringBody('deletenotifnotok');

        }

        }
            else // en cas de non requête AJAX on lève une exception 404
        {
            throw new NotFoundException(__('Cette page n\'existe pas.'));
        }
    }

}
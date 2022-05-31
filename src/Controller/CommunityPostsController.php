<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Event\EventManager;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\NotFoundException;

/**
 * CommunityPosts Controller
 *
 * @property \App\Model\Table\CommunityPostsTable $CommunityPosts
 * @method \App\Model\Entity\CommunityPost[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CommunityPostsController extends AppController
{

  public function beforeFilter(EventInterface $event)
  {
      parent::beforeFilter($event);

      $this->Authentication->allowUnauthenticated(['index','view']);

  }
    /**
     * Index method
     * 
     * Affichages des messages communautaires par utilisateur 
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $communityPosts = $this->paginate($this->CommunityPosts->find()->where(['username_community_post' => $this->request->getParam('username')])
                                                                        ->order(['created' => 'DESC']));
                                                                        
                                                                        
        $this->set(compact('communityPosts'));
    }

    /**
     * View method
     * 
     * Affichage d'un message communautaire
     *
     * @param string|null $id Community Post id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
      public function view()
    {

        $communityPost = $this->CommunityPosts->get($this->request->getParam('idcommunitypost')); // id du message communautaire à voir

          if($this->Authentication->getIdentity()) // si je suis connecté
        {
  
          if($communityPost->auteur != $this->Authentication->getIdentity()->username) // test du blocage
        {
          $checkblock = AppController::checkblock($communityPost->username_community_post, $this->Authentication->getIdentity()->username);
  
            if($checkblock == 'block') // je suis bloqué
          {
            $this->set('userblocked', $checkblock);
          }
  
        }
      }

        $this->set('title' , ''.$communityPost->username_community_post.' | Youtux'); // titre de la page

        $this->set(compact('communityPost'));
    }

    /**
     * Add method
     * 
     * Ajout d'un message communautaire
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

          if($this->request->is('ajax'))
        {

          // test si un commentaire est vide

            if(trim($this->request->getData('communitymessage')) == '')
          {
              return $this->response->withStringBody(json_encode(['response'=> 'emptycommunitymessage'])); // vidéo postée avec succès
          }

          // suppression d'éventuelles balises parasites

            $communitymessage = strip_tags($this->request->getData('communitymessage'));

          // création d'une nouvelle entité commens
        
          $communityPost = $this->CommunityPosts->newEmptyEntity();

            $data = array(
                            'id_community_post' => $this->idcommunitymessage(), // id généré aléatoirement par la fonction idcommunitymessage()
                            'username_community_post' => $this->Authentication->getIdentity()->username,
                            'message_community_post' => AppController::linkify_content($communitymessage),
                            'nb_comm' => 0
                          );

                $communityPost = $this->CommunityPosts->patchEntity($communityPost, $data);

                // si le message communautaire est correctement crée

                    if ($this->CommunityPosts->save($communityPost)) 
                {

                  // renvoi d'une réponse JSON contenant le commentaire et une réponse

                  return $this->response->withType("application/json")->withStringBody(json_encode(['communitypost' => $communityPost, 'response' => 'newcommunitymessageok']));

                }
                  else
                {

                  // renvoi d'une réponse JSON contenant une réponse

                  return $this->response->withStringBody(json_encode(['response'=> 'newcommunitymessagenotok'])); // échec insertion BDD

                }  
        }
          else // en cas de non requête AJAX on lève une exception 404
        {
          throw new NotFoundException(__('Cette page n\'existe pas.'));
        }

    }

    /**
     * Edit method
     * 
     * Modification d'un message communautaire
     *
     * @param string|null $id Community Post id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit()
    {
        if ($this->request->is('ajax')) // requête AJAX uniquement
      {

        // on récupère l'entité correspondant a l'id envoyé en Javascript

        $communitymessage = $this->CommunityPosts->get($this->request->getData('idcommunitymessagetoupdate'));

        // on vérifie si je suis l'auteur du message communautaire : si non renvoi d'une réponse JSON

          if($communitymessage->username_community_post != $this->Authentication->getIdentity()->username)
        {
          return $this->response->withType('application/json')
                                   ->withStringBody(json_encode(['response' => 'notownercommunitymessage']));
        }
          else
        {

          // suppression d'éventuelles balises parasites

          $communitymessage->message_community_post = strip_tags($this->request->getData('communitymessage'));

          // Traitement URL, emoji, hashtag,...

          $communitymessage->message_community_post = AppController::linkify_content($communitymessage->message_community_post);

            if($this->CommunityPosts->save($communitymessage)) // si la modification est correctement faite : renvoi de la réponse, du message communautaire modifié et de l'identifiant du message communautaire
          {
            return $this->response->withType("application/json")->withStringBody(json_encode(['response' => 'updatecommunitymessageok','idcommunitypost' =>  $this->request->getData('idcommunitymessagetoupdate'),'updatedcommunitypost' => $communitymessage->message_community_post]));
          }
            else
          {
            return $this->response->withType("application/json")->withStringBody(json_encode(['response' => 'updatecommunitymessagenotok']));
          }

        }
      }
          else // en cas de non requête AJAX on lève une exception 404
      {
          throw new NotFoundException(__('Cette page n\'existe pas.'));
      }
    }

    /**
     * Delete method
     * 
     * Suppression d'un message communautaire
     *
     * @param string|null $id Community Post id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
 
            if ($this->request->is('ajax')) // requête AJAX uniquement
          {

            // on récupère l'entité correspondant a l'id envoyé en Javascript

              $communityPost = $this->CommunityPosts->get($this->request->input());

            // on vérifie si je suis l'auteur du messsage communautaire

              if($communityPost->username_community_post == $this->Authentication->getIdentity()->username)
            {
                if ($this->CommunityPosts->delete($communityPost)) 
              {
                return $this->response->withStringBody('deletecommunitymessageok'); // renvoi d'une réponse de succès
              }
                else
              {
                return $this->response->withStringBody('deletecommunitymessagenotok'); // renvoi d'une réponse d'échec'
              }

            }
              else
            {
              return $this->response->withStringBody('notownercommunitymessage'); // renvoi d'une réponse indiquant que je ne suis pas l'auteur du message
            }
  
          }
              else // en cas de non requête AJAX on lève une exception 404
          {
              throw new NotFoundException(__('Cette page n\'existe pas.'));
          }
    }

             /**
     * Méthode Idcommunitymessage
     *
     * Calcul d'un id de message communautaire aléatoire
     *
     * Sortie : $idcomm -> id de comm
     *
     *
*/
    private function idcommunitymessage()
  {

    $idcommunitymessageid = rand();

    // on vérifie si il existe déjà

    $query = $this->CommunityPosts->find()
                                  ->select(['id_community_post'])                       
                                  ->where(['id_community_post' => $idcommunitymessageid]);

          if ($query->isEmpty())
           // si il n'existe pas on le renvoi
      {
          return $idcommunitymessageid;
      }
          else // on refait la fonction
      {
          idcommunitymessage(); // ou $this->idcomm();
      }
  }
}

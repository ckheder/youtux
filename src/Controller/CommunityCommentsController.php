<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Event\EventManager;
use Cake\Http\Exception\NotFoundException;

/**
 * CommunityComments Controller
 *
 * @method \App\Model\Entity\CommunityComment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CommunityCommentsController extends AppController
{

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['view']);
        $this->fetchTable('CommunityPosts');
    }

    /**
     * View method
     * 
     * Liste des commentaires d'un message communautaire
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view()
    {
        if($this->request->is('ajax'))
      {
        $communitycomments = $this->paginate($this->CommunityComments->find()->select(['id_community_comment','username_community_comment','community_comment','created','modified','CommunityPosts.username_community_post'])
        ->leftjoin(
            ['CommunityPosts'=>'community_posts'],
            ['CommunityPosts.id_community_post = (CommunityComments.idmessage_community)']
            )
        ->where(['CommunityComments.idmessage_community' => $this->request->getParam('idcommunitypost')])
        
        ->order(['CommunityComments.created' => 'DESC'])); 
                      
        $this->set(compact('communitycomments'));
      }

        else // en cas de non requête AJAX on lève une exception 404
      {
        throw new NotFoundException(__('Cette page n\'existe pas.'));
      }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
      public function add()
    {
          if($this->request->is('ajax'))
        {

          // test si un commentaire est vide

            if(trim($this->request->getData('communitycomment')) == '')
          {
              return $this->response->withStringBody(json_encode(['response'=> 'emptycommunitycomment'])); // vidéo postée avec succès
          }

          // test blocage

            if($this->request->getHeader('Authtest')[0] != $this->Authentication->getIdentity()->username)
          {
              $checkblock = AppController::checkblock($this->request->getHeader('Authtest')[0], $this->Authentication->getIdentity()->username);
            
              if($checkblock == 'block')
            {
              return $this->response->withStringBody(json_encode(['response'=> 'userblocked'])); // renvoi d'une réponse
            }
            
                    }

          // suppression d'éventuelles balises parasites

          $communitycomm = strip_tags($this->request->getData('communitycomment'));

          // création d'une nouvelle entité communitycomment
        
          $communitycomment = $this->CommunityComments->newEmptyEntity();

            $data = array(
                          'id_community_comment' => $this->idcommunitycomment(), // id généré aléatoirement par la fonction idcomment()
                          'username_community_comment' => $this->Authentication->getIdentity()->username,// nom utilisateur
                          'community_comment' => AppController::linkify_content($communitycomm),// commentaire communautaire
                          'idmessage_community' => $this->request->getData('idcommunitypost')
                          );

                $communitycomment = $this->CommunityComments->patchEntity($communitycomment, $data);

                // si le commentaire communautaire est correctement crée

                    if ($this->CommunityComments->save($communitycomment)) 
                {
                  // renvoi d'une réponse JSON contenant le commentaire communautaire et une réponse

                  return $this->response->withType("application/json")->withStringBody(json_encode(['communitycomment' => $communitycomment, 'response' => 'newcommunitycommentok']));

                }
                  else
                {
                  // renvoi d'une réponse JSON contenant une réponse

                  return $this->response->withStringBody(json_encode(['response'=> 'newcommunitycommentnotok'])); // échec insertion BDD

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
     * Modification d'un commentaire communautaire
     *
     * @param string|null $id Community Comment id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
      public function edit($id = null)
    {
        if ($this->request->is('ajax')) // requête AJAX uniquement
      {

          // on récupère l'entité à modifier

          $communitycomment = $this->CommunityComments->get($this->request->getData('idcommentcommunitytoupdate'));

          // on vérifie si je suis l'auteur du commentaire communautaire : si non renvoi d'une réponse JSON

          if($communitycomment->username_community_comment != $this->Authentication->getIdentity()->username)
        {
          return $this->response->withType('application/json')
                                  ->withStringBody(json_encode(['response' => 'notownercommunitycomment']));
        }
          else
        {

          // suppression d'éventuelles balises parasites

          $communitycomment->community_comment = strip_tags($this->request->getData('communitycomment'));

          // Traitement URL, emoji, hashtag,...

          $communitycomment->community_comment = AppController::linkify_content($communitycomment->community_comment);

            if($this->CommunityComments->save($communitycomment)) // si la modification est correctement faite : renvoi de la réponse, du commentaire communautaire modifié et de l'identifiant du commentaire communautaire
          {

            return $this->response->withType('application/json')
                                   ->withStringBody(json_encode(['response' => 'updatecommunitycommentok','idcommunitycomment' => $this->request->getData('idcommentcommunitytoupdate'),'updatedcommunitycomment' => $communitycomment->community_comment]));
          }
            else // sinon renvoi d'une réponse d'échec
          {

            return $this->response->withType('application/json')
                                   ->withStringBody(json_encode(['response' => 'updatecommunitycommentnotok']));
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
     * Suppression d'un commentaire communautaire
     *
     * @param string|null $id Community Comment id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
      public function delete($id = null)
    {
        if ($this->request->is('ajax')) // requête AJAX uniquement
      {

        // on vérifie si je suis bien l'auteur du commentaire communautaire ou que je suis l'auteur d'un message communautaire qui veut supprimer un commentaire communautaire

        $check_community_comment_user = $this->CommunityComments->find()

        ->leftjoin(
          ['CommunityPosts'=>'community_posts'],
          ['CommunityPosts.id_community_post = (CommunityComments.idmessage_community)']
          )

        ->where([
                  'OR' => ['CommunityComments.username_community_comment' => $this->Authentication->getIdentity()->username,'CommunityPosts.username_community_post' => $this->Authentication->getIdentity()->username]])

        ->where(['CommunityPosts.id_community_post' => $this->request->input('json_decode')->idcommunitypost, 'CommunityComments.id_community_comment' => $this->request->input('json_decode')->idcommunitycomment]);


          if($check_community_comment_user->isEmpty()) // si la requête est vide, je ne peut supprimer ce commentaire communautaire -> renvoi d'une réponse
        {
          return $this->response->withStringBody('deletecommunitycommentnotok');
        }

          else
        {
          $entity = $this->CommunityComments->get($this->request->input('json_decode')->idcommunitycomment); // on récupère l'entité correspondant a l'id du commentaire communautaire

            if($this->CommunityComments->delete($entity)) // si l'entité est correctement supprimée
          {
            return $this->response->withStringBody('deletecommunitycommentok'); // renvoi d'une réponse de succès
          }
            else
          {
            return $this->response->withStringBody('deletecommunitycommentnotok'); // renvoi d'une réponse d'échec
          }

        }

      }
          else // en cas de non requête AJAX on lève une exception 404
      {
          throw new NotFoundException(__('Cette page n\'existe pas.'));
      }
    }

             /**
     * Méthode Idcommunitycomment
     *
     * Calcul d'un id de commentaire communautaire aléatoire
     *
     * Sortie : $idcommunitycomment -> id de commentaire communautaire
     *
     *
*/
      private function idcommunitycomment()
    {

      $idcommunitycomment = rand();

      // on vérifie si il existe déjà

      $query =$this->CommunityComments->find()
                                      ->select(['id_community_comment'])
                                      ->where(['id_community_comment' => $idcommunitycomment]);

          if ($query->isEmpty()) // si il n'existe pas on le renvoi
      {
          return $idcommunitycomment;
      }
          else // on refait la fonction
      {
          idcommunitycomment();
      }
    }
}

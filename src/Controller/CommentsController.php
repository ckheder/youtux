<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Event\EventManager;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Exception\NotFoundException;
use App\Event\CommentsListener;

/**
 * Comments Controller
 *
 * @property \App\Model\Table\CommentsTable $Comments
 * @method \App\Model\Entity\Comment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CommentsController extends AppController
{

  public function initialize() : void
  {
    parent::initialize();
  
  
    //listener qui va écouté la création d'un nouveau commentaire
  
    $CommentsListener = new CommentsListener();
  
    $this->getEventManager()->on($CommentsListener);
  
  }

    public $paginate = [
        'limit' => 8,
                            ];

      public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['view']);
        $this->fetchTable('Movies');
    }


    /**
     * View method
     * 
     * Liste des commentaires d'une vidéo
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
      public function view()
    {

        if($this->request->is('ajax'))
      {

        $comments = $this->paginate($this->Comments->find()->select(['id_comm','commentaire','user_comm','created','modified','Movies.auteur'])
                                                          ->leftjoin(
                                                                      ['Movies'=>'movies'],
                                                                      ['Movies.id_movie = (Comments.id_movie)']
                                                                    )
                                                            ->where(['Comments.id_movie' => $this->request->getParam('idmovie')])
        
                                                            ->order(['Comments.created' => 'DESC']));    
                    
          $this->set(compact('comments'));
        
      }

        else // en cas de non requête AJAX on lève une exception 404
      {
        throw new NotFoundException(__('Cette page n\'existe pas.'));
      }
    }

    /**
     * Add method
     * 
     * Ajout d'un commentaire
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
        public function add()
    {
          if($this->request->is('ajax'))
        {

          // détermine si les commentaires sont bloqués

            if($this->request->getData('allowcomm') == 1)
          {
            return $this->response->withStringBody(json_encode(['response'=> 'commblock'])); // renvoi d'une réponse
          }

          // test blocage

          if($this->request->getData('authvideo') != $this->Authentication->getIdentity()->username)
        {
          $checkblock = AppController::checkblock($this->request->getData('authvideo'), $this->Authentication->getIdentity()->username);

            if($checkblock == 'block')
          {
            return $this->response->withStringBody(json_encode(['response'=> 'userblocked'])); // renvoi d'une réponse
          }

        }

          // test si un commentaire est vide

            if(trim($this->request->getData('comment')) == '')
          {
              return $this->response->withStringBody(json_encode(['response'=> 'emptycomm'])); // vidéo postée avec succès
          }

          // suppression d'éventuelles balises parasites

            $comm = strip_tags($this->request->getData('comment'));

          // création d'une nouvelle entité commens
        
            $comment = $this->Comments->newEmptyEntity();

            $data = array(
                'id_comm' => $this->idcomm(), // id généré aléatoirement par la fonction idcomm()
                'commentaire' => AppController::linkify_content($comm),
                'id_movie' => $this->request->getData('idvideo'),
                'user_comm' => $this->Authentication->getIdentity()->username
              );

                $comment = $this->Comments->patchEntity($comment, $data);

                // si le commentaire est correctement crée

                    if ($this->Comments->save($comment)) 
                {

                    if($this->request->getData('authvideo') != $this->Authentication->getIdentity()->username) // si je ne commente pas ma propre vidéo, test de l'acceptation des notifications
                  {
  
                      if(AppController::check_notif('comm', $this->request->getData('authvideo')) == 'oui') // si l'auteur du tweet accepte les notifications de commentaire
                    {
  
                      // Evènement de création d'une notification de commentaire
  
                      $event = new Event('Model.Comments.afteradd', $this, ['data' => $data, 'authormovie' => $this->request->getData('authvideo')]);
  
                      $this->getEventManager()->dispatch($event);
   
                    }
                  }

                  // renvoi d'une réponse JSON contenant le commentaire et une réponse

                  return $this->response->withType("application/json")->withStringBody(json_encode(['comment' => $comment, 'response' => 'newcommok']));

                }
                  else
                {

                  // renvoi d'une réponse JSON contenant une réponse

                  return $this->response->withStringBody(json_encode(['response'=> 'newcommnotok'])); // échec insertion BDD

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
     * Modification d'un commentaire
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
      public function edit($id = null)
    {
        if ($this->request->is('ajax')) // requête AJAX uniquement
      {

        // on récupère l'entité à modifier

        $comment = $this->Comments->get($this->request->getData('idcomm'));

        // on vérifie si je suis l'auteur du commentaire : si non renvoi d'une réponse JSON

          if($comment->user_comm != $this->Authentication->getIdentity()->username)
        {
          return $this->response->withType('application/json')
                                   ->withStringBody(json_encode(['response' => 'notownercomm']));
        }
          else
        {

          // suppression d'éventuelles balises parasites

          $comment->commentaire = strip_tags($this->request->getData('comment'));

          // Traitement URL, emoji, hashtag,...

          $comment->commentaire = AppController::linkify_content($comment->commentaire);

            if($this->Comments->save($comment)) // si la modification est correctement faite : renvoi de la réponse, du commentaire modifié et de l'identifiant du commentaire
          {

            return $this->response->withType('application/json')
                                   ->withStringBody(json_encode(['response' => 'updatecommok','idcomm' => $this->request->getData('idcomm'),'comment' => $comment->commentaire]));
          }
            else // sinon renvoi d'une réponse d'échec
          {

            return $this->response->withType('application/json')
                                   ->withStringBody(json_encode(['response' => 'updatecommnotok']));
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
     * Supprimer un commentaire
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if ($this->request->is('ajax')) // requête AJAX uniquement
      {

        // on vérifie si je suis bien l'auteur du commentaire ($this->request->input('json_decode')->idcomm) ou que je suis l'auteur d'une vidéo qui veut supprimer un commentaires

        $check_comm_user = $this->Comments->find()

        ->leftjoin(
                ['Movies'=>'movies'],
                ['Movies.id_movie = (Comments.id_movie)']
                )

        ->where([
                  'OR' => ['Comments.user_comm' => $this->Authentication->getIdentity()->username,'Movies.auteur' => $this->Authentication->getIdentity()->username]])

        ->where(['Movies.id_movie' => $this->request->input('json_decode')->idmovie, 'Comments.id_comm' => $this->request->input('json_decode')->idcomm]);


          if($check_comm_user->isEmpty()) // si la requête est vide, je ne peut supprimer ce commentaire -> renvoi d'une réponse
        {
          return $this->response->withStringBody('deletecommnotok');
        }

            else
        {
          $entity = $this->Comments->get($this->request->input('json_decode')->idcomm); // on récupère l'entité correspondant a l'id du comm

            if($this->Comments->delete($entity)) // si l'entité est correctement supprimée
          {
            return $this->response->withStringBody('deletecommok'); // renvoi d'une réponse de succès
          }
            else
          {
            return $this->response->withStringBody('deletecommnotok'); // renvoi d'une réponse d'échec
          }

        }

      }
          else // en cas de non requête AJAX on lève une exception 404
      {
          throw new NotFoundException(__('Cette page n\'existe pas.'));
      }
    }

    /**
 * Méthode Actioncomm
 *
 * Activation / Désactivation des commentaires pour un tweet donné
 *
 * Paramètres (JSON): id de la vidéo , action à effectué
 *
 * Par défaut : 0 ->commentaire activé et 1 -> commentaire désactivé
 */
    public function actioncomm()
{

    if ($this->request->is('ajax')) // requête AJAX uniquement
  {

    $jsonData = $this->request->input('json_decode'); // récupération des informations envoyées en JSON
    
    $idmovie = $jsonData->idmovie; //identifiant de la vidéo concerné

    $action = $jsonData->action; // 0 ou 1 : si 0 je désactive les commentaires, si 1 j'active les commentaires

    // mise à jour en BDD

    $statement = ConnectionManager::get('default')->prepare('UPDATE movies SET allow_comment = :action WHERE id_movie = :idmovie');

    $statement->bindValue('action', $action, 'boolean');

    $statement->bindValue('idmovie', $idmovie, 'integer');

    $statement->execute();

    $rowCount = $statement->rowCount();

      if ($rowCount == 1) // mise à jour réussie
    {
      return $this->response->withStringBody('updatestatutcommok'); // renvoi d'une réponse de succès
    }

      elseif ($rowCount == 0) // échec mise à jour information
    {
      return $this->response->withStringBody('updatestatutcommnotok'); // renvoi d'une réponse d'échec
    }

  }
    else // en cas de non requête AJAX on lève une exception 404
  {
    throw new NotFoundException(__('Cette page n\'existe pas.'));
  }
}

         /**
     * Méthode Idcomm
     *
     * Calcul d'un id de comm aléatoire
     *
     * Sortie : $idcomm -> id de comm
     *
     *
*/
    private function idcomm()
  {

    $idcomm = rand();

    // on vérifie si il existe déjà

    $query = $this->Comments->find()
                            ->select(['id_comm'])
                            ->where(['id_comm' => $idcomm]);

            if ($query->isEmpty()) // si il n'existe pas on le renvoi
        {
            return $idcomm;
        }
            else // on refait la fonction
        {
            idcomm(); // ou $this->idcomm();
        }
  }
}

<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Event\EventManager;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Exception\NotFoundException;
use Cake\Routing\Router;

/**
 * Block Controller
 *
 * @method \App\Model\Entity\Block[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BlockController extends AppController
{
    /**
     * Index method
     * 
     * Récupération des utilisateurs bloqués par l'utilisateur authentifié
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Youtux | Utilisateurs bloqués'); // titre de la page

        $userblocked = $this->Block->find()

        ->select(['bloque'])
        
        ->where(['bloqueur' =>  $this->Authentication->getIdentity()->username ])

        ->order((['bloque' => 'ASC']));

        $this->set('userblocked', $this->Paginator->paginate($userblocked, ['limit' => 10]));
    }

     /**
     * Méthode add
     *
     * Création d'un blocage
     *
     */
        public function add()
    {

            if ($this->request->is('ajax')) // requête AJAX uniquement
        {

            $blocked_user = $this->request->input(); // nom de la personne bloquée

            // Vérification si il n'existe pas déjà un blocage :  si non, création d'une nouvelle entité

                if(AppController::checkblock($this->Authentication->getIdentity()->username, $blocked_user) == 'noblock')
            {

                $newblock = $this->Block->newEmptyEntity();

                $newblock->id_block = $this->idblock();

                $newblock->bloqueur = $this->Authentication->getIdentity()->username;

                $newblock->bloque = $blocked_user;

                    if ($this->Block->save($newblock)) // création d'un blocage réussie
                {

                    // suppression des commentaires des vidéos

                    $mymovies = $this->fetchTable('Movies')->find()->select(['id_movie'])->where(['auteur' => $this->Authentication->getIdentity()->username]);

                    $this->fetchTable('Comments')->deleteAll(['user_comm' => $blocked_user,'id_movie IN' => $mymovies]);

                    // suppression des commentaires sur les messages communautaires

                    $mycommunityposts = $this->fetchTable('CommunityPosts')->find()->select(['id_community_post'])->where(['username_community_post' => $this->Authentication->getIdentity()->username]);

                    $this->fetchTable('CommunityComments')->deleteAll(['username_community_comment' => $blocked_user,'idmessage_community IN' => $mycommunityposts]);
                
                    return $this->response->withStringBody('newblockok');
                                        
                }
                    else // échec création de blocage
                {
                    return $this->response->withStringBody('newblocknotok');
                                        
                }

            }
                else // blocage existant
            {
                return $this->response->withStringBody('alreadyblock');
                                        
            }
    }
            else // en cas de non requête AJAX on lève une exception 404
        {
            throw new NotFoundException(__('Cette page n\'existe pas.'));
        }
    }

    /**
     * Méthode delete
     *
     * Suppression d'un blocage
     *
     */
        public function delete()
    {

            if ($this->request->is('ajax')) // requête AJAX uniquement
        {

            $unblocked_user = $this->request->input(); //nom de la personne concerné par la suppression

            $statement = ConnectionManager::get('default')->prepare(
            'DELETE FROM block WHERE bloqueur = :bloqueur AND bloque = :bloque');

            $statement->bindValue('bloqueur', $this->Authentication->getIdentity()->username, 'string');

            $statement->bindValue('bloque', $unblocked_user, 'string');

            $statement->execute();

            // Récupération du nombre de ligne affectée

            $rowCount = $statement->rowCount();

                if ($rowCount == 1) // blocage supprimée avec succès , renvoi d'une réponse au format JSON
            {

                    if($this->request->referer() == '/b/list') // je vient de la page de gestion des blocage
                {
                    return $this->response->withStringBody('removeblockfromlistok');
                                        
                }
                    else
                {
                    return $this->response->withStringBody('removeblockok'); // je viens d'une page de profil
                                        
                }
                
            }
                elseif ($rowCount == 0) // échec suppression, renvoi d'une réponse au format JSON
            {
                return $this->response->withStringBody('removeblocknotok');
                                        
            }

        }
            else // en cas de non requête AJAX on lève une exception 404
        {
            throw new NotFoundException(__('Cette page n\'existe pas.'));
        }
    }

        /**
     * Méthode Idblock
     *
     * Calcul d'un id de blocage aléatoire
     *
     * Sortie : $idblock -> id de blocage
     *
     *
    */
        private function idblock()
    {

        $idblock = rand();

        // on vérifie si il existe déjà

        $query = $this->Block->find()
                                ->select(['id_block'])
                                ->where(['id_block' => $idblock]);

          if ($query->isEmpty()) // si il n'existe pas on le renvoi
      {
          return $idblock;
      }
          else // on refait la fonction
      {
          idblock();
      }

    }

}

<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Event\EventManager;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Exception\NotFoundException;
use Cake\Routing\Router;
use App\Event\FollowsListener;

/**
 * Follows Controller
 *
 * @property \App\Model\Table\FollowsTable $Follows
 * @method \App\Model\Entity\Follow[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FollowsController extends AppController
{

    public function initialize() : void
    {
      parent::initialize();
    
    
      //listener qui va écouté la création d'un nouveau commentaire
    
      $FollowsListener = new FollowsListener();
    
      $this->getEventManager()->on($FollowsListener);
    
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['usersubscriptions']);
    }

    /** Méthode Index
     * 
     * Liste des mes abonnements
     * 
     */

        public function index()
        
     {

        $this->set('title', 'Youtux | Mes abonnements'); // titre de la page

        $mysubscriptions = $this->getusersubscriptions($this->Authentication->getIdentity()->username);

        $this->set('mysubscriptions', $this->Paginator->paginate($mysubscriptions, ['limit' => 10]));

     }

    /**
     * Méthode Usersusbscriptions
     *
     * Retourne la liste des abonnements du profil en cours de visite
     * 
     * Paramètre : username donné en URL
     */

        public function usersubscriptions()
    {
            if ($this->request->is('ajax')) // requête est de type AJAX
        {

            $usersubscription = $this->getusersubscriptions($this->request->getParam('username'));

            $this->set('usersubscription', $this->Paginator->paginate($usersubscription, ['limit' => 10]));

        }

            else
        {
            throw new NotFoundException(__('Cette page n\'existe pas.'));
        }

    }

    /**
     * Méthode subscriptions : affichage des vidéos des utilisateurs que je suis
     *
     */

        public function subscriptions()
    {

        $this->set('title', 'Youtux | Abonnements'); // titre de la page

        // Récupération de mes abonnements

        $usersubscriptions = $this->Follows->find()

            ->select(['following'])

            ->where(['follower' => $this->Authentication->getIdentity()->username]);

        // Récupération des vidéos postées par mes abonnements

            $subscriptions = $this->fetchTable('Movies')->find()
                                        ->select([
                                                    'id_movie',
                                                    'titre',
                                                    'filename',
                                                    'description',
                                                    'auteur',
                                                    'nb_vues',
                                                    'created'
                                                    ])

                                        ->where(['auteur IN' => $usersubscriptions])
                                        ->order(['created' => 'DESC']);
                                                
            $this->set('subscriptions', $this->paginate($subscriptions, ['limit' => 4]));
        
    }


 /**
     * Méthode add
     *
     * Ajout d'un nouvel abonnement
     *
     * Paramètre : username donné en URL
     */
        public function add()
    {

            if ($this->request->is('ajax')) // requête AJAX uniquement
        {

            $followed_user = $this->request->input(); //nom de la personne à ajouté

            // vérification de l'existence d'un abonnement

            $check_abo  = $this->Follows->find()
                                        ->where(['follower' => $this->Authentication->getIdentity()->username, 'following' => $followed_user]);


            // Pas d'abonnement, on en crée un nouveau

                if($check_abo->isEmpty())
            {
                              

            // création d'un nouvel abonnement

                $newfollow = $this->Follows->newEmptyEntity();

                $newfollow->id_follow = $this->idfollow();

                $newfollow->follower = $this->Authentication->getIdentity()->username;

                $newfollow->following = $followed_user;

                if ($this->Follows->save($newfollow)) // création d'abonnement réussie, renvoi d'une réponse au format JSON
            {


                    if(AppController::check_notif('follow', $followed_user) == 'oui') // si l'auteur du tweet accepte les notifications de commentaire
                  {

                    // Evènement de création d'une notification de commentaire

                    $event = new Event('Model.Follows.afteradd', $this, ['data' => $newfollow]);

                    $this->getEventManager()->dispatch($event);
                    
                  }
                
                    return $this->response->withType('application/json')
                                        ->withStringBody(json_encode(['response' => 'newfollowok']));
                
            }
                     else // abonnement réussie
                {
                    return $this->response->withType('application/json')
                                        ->withStringBody(json_encode(['response' => 'newfollownotok']));
                }

            //}
              //else // impossible de s'abonner
            //{
               // return $this->response->withType('application/json')
                                    //->withStringBody(json_encode(['Result' => 'abonnementnonajoute']));
           // }
                
            }
            else
            {
                return $this->response->withType('application/json')
                                        ->withStringBody(json_encode(['response' => 'alreadyfollow']));


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
     * Suppressiopn d'un abonnement
     *
     */
        public function delete()
    {

            if ($this->request->is('ajax')) // requête AJAX uniquement
        {

            $unfollowed_user = $this->request->input(); //nom de la personne concerné par la suppression

            $statement = ConnectionManager::get('default')->prepare(
            'DELETE FROM follows WHERE follower = :follower AND following = :following');

            $statement->bindValue('follower', $this->Authentication->getIdentity()->username, 'string');

            $statement->bindValue('following', $unfollowed_user, 'string');

            $statement->execute();

            // Récupération du nombre de ligne affectée

            $rowCount = $statement->rowCount();

                if ($rowCount == 1) // abonnement supprimée avec succès , renvoi d'une réponse au format JSON
            {

                    if($this->request->referer() == '/f/list') // je vient de la page de gestion des abonnements
                {
                    return $this->response->withType('application/json')
                                        ->withStringBody(json_encode(['response' => 'removefollowfromlistok']));
                }
                    else
                {
                    return $this->response->withType('application/json')
                                        ->withStringBody(json_encode(['response' => 'removefollowok']));
                }
                
            }
                elseif ($rowCount == 0) // échec suppression, renvoi d'une réponse au format JSON
            {
                return $this->response->withType('application/json')
                                        ->withStringBody(json_encode(['response' => 'removefollownotok']));
            }

        }
            else // en cas de non requête AJAX on lève une exception 404
        {
            throw new NotFoundException(__('Cette page n\'existe pas.'));
        }
    }

    /**
     * Méthode Idfollow
     *
     * Calcul d'un id de d'abonnement aléatoire
     *
     * Sortie : $idfollow -> id de follow
     *
     *
    */
        private function idfollow()
    {

        $idfollow = rand();

        // on vérifie si il existe déjà

        $query = $this->Follows->find()
                                ->select(['id_follow'])
                                ->where(['id_follow' => $idfollow]);

          if ($query->isEmpty()) // si il n'existe pas on le renvoi
      {
          return $idfollow;
      }
          else // on refait la fonction
      {
          idfollow(); // ou $this->idfollow();
      }

    }

    /**
     * Méthode Getusersubscriptions
     *
     * Récupération de tous les abonnés de l'utilisateur donné en paramètre par ordre alphabétique
     *
     * Paramètre : $username -> nom de la personne dont on recherche les abonnés
     * 
     * Sortie : $usersubscription -> Liste des abonnés d'un utilisateur
     *
     *
    */

        private function getusersubscriptions($username)
    {
        $usersubscriptions = $this->Follows->find()

                                            ->select(['following'])

                                            ->where(['follower' =>  $username ])

                                            ->order((['following' => 'ASC']));

        return $usersubscriptions;
    }


}

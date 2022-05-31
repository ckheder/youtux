<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Event\EventManager;
use Cake\Http\Exception\NotFoundException;
use Cake\Datasource\ConnectionManager;

/**
 * FavoriteMovies Controller
 *
 * @method \App\Model\Entity\FavoriteMovie[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FavoriteMoviesController extends AppController
{

        public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->fetchTable('Movies');
    }
    /**
     * Index method
     * 
     * Liste des vidéos favorites d'un utilisateur
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
        public function index()
    {

        $this->set('title', 'Youtux | Mes vidéos favorites'); // titre de la page

        $favoriteMovies = $this->paginate($this->FavoriteMovies->find()->select(['id_favorite_movies',
                                                                                'favorite_movies',
                                                                                'Movies.id_movie',
                                                                                'Movies.titre',
                                                                                'Movies.filename',
                                                                                'Movies.description',
                                                                                'Movies.auteur',
                                                                                'Movies.nb_like',
                                                                                'Movies.nb_comment',
                                                                                'Movies.nb_vues',
                                                                                'Movies.created'])
        ->leftjoin(
                    ['Movies'=>'movies'],
                    ['Movies.id_movie = (FavoriteMovies.favorite_movies)']
                )

        ->where(['username_favorite_movies' => $this->Authentication->getIdentity()->username])
        
        ->order(['Movies.created' => 'DESC']));

        $this->set('favoriteMovies', $favoriteMovies);
    }

    /**
     * Add method
     * 
     * Création d'un favori
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
        public function add()
    {
        if ($this->request->is('ajax')) // requête AJAX uniquement
      {

          $favoritemovietoadd = $this->request->input(); // id de la vidéo à ajouter en favori

          // vérification de l'existence d'un favori pour la vidéo à ajouter

          $check_favorite  = $this->FavoriteMovies->find()
                                                    ->where(['username_favorite_movies' => $this->Authentication->getIdentity()->username,
                                                    'favorite_movies' => $favoritemovietoadd]);


          // Pas de favori, on en crée un nouveau

              if($check_favorite->isEmpty())
            {
                            //if(AppController::get_type_profil($username ) == 'prive') // si c'est un profil prive
          //{
            //$etat = 0; // demande d'abonnement car profil prive
         // }
            //else
          //{
            //$etat = 1; // abonnement validé car profil public
          //}

          // création d'un nouvel abonnement

              $newfavorite = $this->FavoriteMovies->newEmptyEntity();

              $newfavorite->id_favorite_movies  = $this->idfavorite(); // id aléatoire de favori

              $newfavorite->username_favorite_movies = $this->Authentication->getIdentity()->username; // utilisateur connecté

              $newfavorite->favorite_movies = $favoritemovietoadd; // id de la vidéo en favori

              if ($this->FavoriteMovies->save($newfavorite)) // création de favori réussi, renvoi d'une réponse au format JSON contenant l'id nouvellement crée pour le bouton de traitement en JS
            {

              //$notifabo = 'non'; // variable qui va servir à ,si elle vaut 'oui', à émettre un évent Node JS de nouvelle notification

                  //if(AppController::check_notif('abonnement', $username ) == 'oui') // si la personne a laquell je m'abonne accepte les notifications d'abonnement
              //{

                  // Evènement de création d'une notification de d'abonnement ou de demande

                  //$event = new Event('Model.Abonnement.afteradd', $this, ['data' => $data]);

                  //$this->getEventManager()->dispatch($event);

                 // $notifabo = 'oui';

              //}

                  //if($etat == 0) // demande d'abonnement réussie
              //{

                  return $this->response->withType('application/json')
                                   ->withStringBody(json_encode(['response' => 'newfavoriteok','idfavorite' => $newfavorite['id_favorite_movies']]));
              //}
          }
                   else // impossible de crée un favori
              {
                  return $this->response->withStringBody('newfavoritenotok');
                                      
              }
            
          }
            else // favori existant
          {
              return $this->response->withStringBody('alreadyfavorite');
                                      
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
     * Suppression d'un favori
     *
     * @param string|null $id Favorite Movie id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
        public function delete($id = null)
    {

        if($this->request->is('ajax')) // requête AJAX
      {

            $entity_favorite_movie = $this->FavoriteMovies->get($this->request->input()); // récupération de l'entité 'favorite movies' correspondante

                if ($this->FavoriteMovies->delete($entity_favorite_movie)) // favori supprimée avec succès , renvoi d'une réponse au format TEXT
            {

                return $this->response->withStringBody('deletefavoritemovieok');
             
            }
                else  // échec suppression, renvoi d'une réponse au format TEXT
            {
                return $this->response->withStringBody('deletefavoritemovienotok');
            }

        }
            else // en cas de non requête AJAX on lève une exception 404
        {
            throw new NotFoundException(__('Cette page n\'existe pas.'));
        }

    }

/*
     * Méthode Idfavorite
     *
     * Calcul d'un id de favori aléatoire
     *
     * Sortie : $idfollow -> id de follow
     *
     *
    */
        private function idfavorite()
    {

        $idfavorite = rand();

        // on vérifie si il existe déjà

        $query = $this->FavoriteMovies->find()
                                ->select(['id_favorite_movies'])
                                ->where(['id_favorite_movies' => $idfavorite]);

          if ($query->isEmpty()) // si il n'existe pas on le renvoi
      {
          return $idfavorite;
      }
          else // on refait la fonction
      {
          idfavorite(); // ou $this->idfollow();
      }

    }
}

<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\NotFoundException;
use Cake\Datasource\ConnectionManager;

/**
 * Movies Controller
 *
 * @property \App\Model\Table\MoviesTable $Movies
 * @method \App\Model\Entity\Movie[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MoviesController extends AppController
{

    public function initialize() : void
  {
      parent::initialize();

      $this->Authentication->allowUnauthenticated(['view', 'index','categorie','home']); // on autorise les gens non auth à voir les profil public
  }
    /**
     * Index method
     * 
     * Liste des vidéos d'un utilisateur par ordre décroissant de date
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
      public function index()
    {
          
        if ($this->request->is('ajax')) // si la requête est de type AJAX, on charge la layout spécifique
      {
          $this->viewBuilder()->setLayout('ajax');
      }
          else
      {
          $this->viewBuilder()->setLayout('profil');
    
          $this->set('title' , ''.$this->request->getParam('username').' | Youtux'); // titre de la page   

      }

      // on vérifie si l'utilisateur existe

      $check_user = $this->fetchTable('Users')->find()
                                              ->where(['username' => $this->request->getParam('username')]);

      $result_check_user = $check_user->first();

        if (is_null($result_check_user))
      {
        $this->set('unknownuser',0);
      }
      else
      {
        $movie = $this->Movies->find()
                              ->select(['id_movie','titre','filename','nb_vues','created','type'])
                              ->where(['auteur' => $this->request->getParam('username')])
                              ->order(['created' => 'DESC']);

          $this->set('movie', $movie);
      }

    
    }

        /**
     * Home method
     * 
     * Liste des vidéos de tous par ordre décroissant de date et publiée
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function home()
    {
          
          $this->set('title' , 'Youtux'); // titre de la page   
      

          $movie = $this->Movies->find()
                                ->select(['id_movie','titre','auteur','filename','nb_vues','created'])
                                ->where(['type' => 'released'])
                                ->order(['created' => 'DESC']);

          $this->set('movie', $movie);
        
    }
    
    /**
     * View method
     * 
     * Affichage d'une vidéo
     *
     * @param string|null $id Movie id.
     * @return \Cake\Http\Response|null|void Renders view
     */
      public function view()
    {
      $movie = $this->Movies->get($this->request->getParam('idmovie')); // on récupère l'entité vidéo par id

        if($this->Authentication->getIdentity()) // si je suis connecté et pas l'auteur de la vidéo, test si je suis bloqué par ce dernier 
      {

        if($movie->auteur != $this->Authentication->getIdentity()->username)
      {
        $checkblock = AppController::checkblock($movie->auteur, $this->Authentication->getIdentity()->username);

          if($checkblock == 'block') // je suis bloqué
        {
          $this->set('userblocked', $checkblock);
        }

      }
    }

      $this->set('title' , ''.$movie->titre.' | Youtux'); // titre de la page

      $this->set(compact('movie'));
    }


    /**
     * Add method
     * 
     * Ajouter/Envoyer une vidéo
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
      public function add()
    {

          if ($this->request->is('post')) // requête POST       
        {

                // si le fichier existe et à bien était envoyé
            
                if($this->request->getData('videofilename') && $this->request->getData('videofilename')->getError() != 4) 
            {
  
                $filename_video = $this->uploadvideo($this->request->getData('videofilename')); // traitement de l'envoi de la vidéo

                if($filename_video == 'noupload') // échec du traitement de la vidéo
              {
                return $this->response->withStringBody('uploadfail');
              }

                $movie = $this->Movies->newEmptyEntity();

                $data = array(
                                'id_movie' => $this->idmovie(),
                                'titre' => $this->request->getData('titre'),
                                'filename' => $filename_video,
                                'description' => AppController::linkify_content($this->request->getData('description')),
                                'auteur' => $this->Authentication->getIdentity()->username,
                                'categorie' => $this->request->getData('categorie'), 
                                'nb_like' =>0,
                                'nb_comment' => 0,
                                'nb_vues' => 0,
                                'type' => $this->request->getData('type'), // publiéee / non publiée
                                'allow_comment' => $this->request->getData('allow_comment') // commentaire autorisé ou non : 0 -> oui | 1 -> non
                              );

                $movie = $this->Movies->patchEntity($movie, $data);
      
                  if ($this->Movies->save($movie)) 
                {

                  return $this->response->withStringBody('newvideook'); // vidéo postée avec succès

                }
                  else
                {

                  return $this->response->withStringBody('newvideonotok'); // échec insertion BDD

                }
            }
              else
            {
              return $this->response->withStringBody('uploadfail'); // échec envoi vidéo
            }
            
        }
          else // accès HTTP -> titre de page
        {
            $this->set('title', 'Nouvelle vidéo - Youtux');
        }

    }

    /**
     * Delete method
     * 
     * Suppression d'une vidéo
     *
     * @param string|null $id Movie id.
     * @return \Cake\Http\Response|null|void Response to AJAX request.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
      public function delete($id = null)
    {

        if($this->request->is('ajax')) // requête AJAX
      {
          $movietodelete = $this->Movies->get($this->request->input()); // récupération de l'entité à supprimer

        // on vérifie que l'utilisateur connecté est bien le rppriétaire de la vidéo : si non, renvoi d'une réponse

          if($movietodelete->auteur != $this->Authentication->getIdentity()->username)
        {
          return $this->response->withStringBody('notownermovie');
        }

          if (unlink(WWW_ROOT . 'users/'.$this->Authentication->getIdentity()->username.'/'.$movietodelete->filename.'') AND $this->Movies->delete($movietodelete))
        {
          return $this->response->withStringBody('deletemovieok'); // suppression de fichier et d'entité réussie
        }

          else
        {
          return $this->response->withStringBody('deletemovienotok'); // échec suppression de fichier et d'entité
        }
      }
        else
      {
        throw new NotFoundException(__('Cette page n\'existe pas.'));
      }
    }

        /**
     * Released method
     * 
     * Publication d'une vidéo prcédemment uploadé mais non disponible
     *
     * @param string|null $id Movie id.
     * @return \Cake\Http\Response|null|void Response to AJAX request.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function released()
    {

        if($this->request->is('ajax')) // requête AJAX
      {
          $movietoreleased = $this->Movies->get($this->request->input()); // récupération de l'entité à mettre à jour

        // on vérifie que l'utilisateur connecté est bien le proriétaire de la vidéo : si non, renvoi d'une réponse

          if($movietoreleased->auteur != $this->Authentication->getIdentity()->username)
        {
          return $this->response->withStringBody('notownermovie');
        }

        // on vérifie que la vidéo à publiée ne l'est pas déjà

          elseif ($movietoreleased->type == 'released')
        {
          return $this->response->withStringBody('alreadyreleased');
        }

          else
        {
          $movietoreleased->type = 'released'; // mise à jour de l'entité

            if($this->Movies->save($movietoreleased))
          {
            return $this->response->withStringBody('releasedmovieok'); // mise à jour réussie
          }
            else
          {
            return $this->response->withStringBody('releasedmovienotok'); // échec mise à jour
          }
          
        }

      }
        else
      {
        throw new NotFoundException(__('Cette page n\'existe pas.'));
      }
    }

        /**
     * Mymovies method
     * 
     * Affichage de toutes mes vidéos en vue d'une publication ou d'une suppression
     *
     * @param string|null $id Movie id.
     * @return \Cake\Http\Response|null|void Response to AJAX request.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
      public function mymovies()
    {

      $this->set('title' , 'Mes vidéos | Youtux'); // titre de la page   
    
        $movie = $this->Movies->find()
                              ->select(['id_movie','titre','nb_vues','created','type'])
                              ->where(['auteur' => $this->Authentication->getIdentity()->username])
                              ->order(['created' => 'DESC']);

        $this->set('movie', $movie);

    }
    /** 
    * Deletemychannel method
    * 
    * Suppression de toutes mes vidéos
    *
    * @param string|null $id Movie id.
    * @return \Cake\Http\Response|null|void Response to AJAX request.
    * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    */
     public function deletechannel()
   {

      if ($this->request->is('ajax')) // requête POST       
    {

        if($this->request->is('post'))
      {

      // suppression des vidéos

      $moviestatement = ConnectionManager::get('default')->prepare('DELETE FROM movies WHERE auteur = :authname');

      $moviestatement->bindValue('authname',$this->Authentication->getIdentity()->username, 'string');
  
      $moviestatement->execute();

      $rowCountMovie = $moviestatement->rowCount();

      // suppression des posts communautaires

      $communitypoststatement = ConnectionManager::get('default')->prepare('DELETE FROM community_posts WHERE username_community_post = :authname');

      $communitypoststatement->bindValue('authname',$this->Authentication->getIdentity()->username, 'string');
  
      $communitypoststatement->execute();

      $rowCountCommunityPost = $communitypoststatement->rowCount();


            if ($rowCountMovie >= 1 AND $rowCountCommunityPost >= 1) // mise à jour réussie
          {
            return $this->response->withStringBody('deletechannelok'); // vidéo postée avec succès
        }
          else
        {
          return $this->response->withStringBody('deletechannelnotok'); // échec insertion BDD
        }   
    }
      else
    {
      throw new NotFoundException(__('Cette page n\'existe pas.'));
    }

   }
   else
   {
    $this->set('title', 'Supprimer ma chaîne - Youtux');
   }
  }

       /**
     * Uploadvideo method
     *
     * @param string|null $file video file.
     */

      private function uploadvideo($file)
    {
        if($file->getError() == 0) // si pas d'erreur d'envoi
      {
        // type MIME autorisé

        $videoMimeTypes = array( 
                                'video/mp4',
                                'video/webm'
                                );

          if($file->getSize() > 6291456) // taille du fichier
        {
          return $this->response->withStringBody('sizenotok'); // fichier trop gros
        }

          if(!in_array($file->getClientMediaType(), $videoMimeTypes)) // test du type MIME
        {
          return $this->response->withStringBody('typenotok'); // type MIME incorrect
        }

        // dossier de destination

          $targetPath = 'users/'.$this->Authentication->getIdentity()->username.'/'.$file->getClientFilename().'';

        // déplacement fichier

          $file->moveTo($targetPath);

        return $file->getClientFilename(); // on retourne le nom du fichier
      }
        else 
      {
        return $this->response->withStringBody('noupload');
      }
    }

    /** Affichage par catégorie de vidéo */

      public function categorie()
    {

        if($this->request->getParam('channel') == 'uncategorized')
      {
        throw new NotFoundException(__('Cette page n\'existe pas.'));
      }
          
      $this->set('title' , 'Youtux'); // titre de la page   
      
      $this->set('moviecat', $this->paginate($this->Movies->find()
                                                            ->select(['id_movie','titre','description','filename','nb_vues','created','auteur'])
                                                            ->where(['categorie' => $this->request->getParam('channel')])
                                                            ));
    }

             /**
     * Méthode Idmovie
     *
     * Calcul d'un id de vidéo aléatoire
     *
     * Sortie : $idmovie -> id de vidéo
     *
     *
*/
  private function idmovie()
{

    $idmovie = rand();

    // on vérifie si il existe déjà

    $query = $this->Movies->find()
                            ->select(['id_movie'])
                            ->where(['id_movie' => $idmovie]);

            if ($query->isEmpty())
        {
            return $idmovie;
        }
            else
        {
            idmovie(); // ou $this->idmovie();
        }
}
}

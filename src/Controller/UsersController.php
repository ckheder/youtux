<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Event\EventManager;
use App\Event\UsersListener;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

        public function initialize() : void
    {
        parent::initialize();

      //listener qui va écouté la création d'un nouvelle utilisateur

        $UsersListener = new UsersListener();

        $this->getEventManager()->on($UsersListener);

    }

        public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['add', 'view','login']);
    }


    /**
     * Add method
     * 
     * Ajout d'un nouvel utilisateur
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
        public function add()
    {
        $this->set('title', 'Youtux | Inscription'); // titre de page

        $user = $this->Users->newEmptyEntity();

                if ($this->request->is('post')) // requête POST
            {
                $user = $this->Users->patchEntity($user, $this->request->getData());

                    if ($this->Users->save($user)) 
                {

                // connexion manuelle de l'utilisateur
                
                  $this->Authentication->setIdentity($user); 

                // Création de la ligne settings, du dossier utilisateur, avatar par défaut

                  $event = new Event('Model.Users.afteradd', $this, ['user' => $user]);

                  $this->getEventManager()->dispatch($event);

                // message de réussite

                  $this->Flash->success(__('Inscription réussie, bienvenue '.h($this->request->getData('username')).' sur Youtux.'));

                // redirection vers le nouveau profil

                  return $this->redirect('/'.$this->Authentication->getIdentity()->username.'');

                }
                else // on retourne les messages d'erreurs
            {
                    if($user->getErrors())
                {
                  $error_msg = [];
  
                    foreach($user->getErrors() as $errors)
                  {
                        if(is_array($errors))
                      {
                          foreach($errors as $error)
                        {
                              $error_msg[]    =   $error;
                        }
                      }
  
                        else
                      {
                          $error_msg[]    =   $errors;
                      }
                  }
  
                      if(!empty($error_msg))
                  {
                      $this->Flash->error(
                          __("<ul><li>".implode("</li><li>", $error_msg)."</li></ul>"), ['escape' => false])
                      ;
                      
                  }
              }
            }     
        }
    }

        /**
     * View method
     *
     * Affichage des informations sur un utilisateur 
     * 
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view()
    {
        $this->viewBuilder()->setLayout('ajax');
    
        $user = $this->Users->find()
                            ->select(['description','pays','created'])
                            ->where(['username' => $this->request->getParam('username')]);

        $this->set(compact('user'));
    }

    /**
     * Edit method
     * 
     * Mise à jour des informations d'un utilisateur
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit()
    {
        if ($this->request->is('ajax')) // requête POST       
        {

              if ($this->request->is(['post'])) 
            { // requête POST

                $user = $this->Users->get($this->Authentication->getIdentity()->id); // récupération de mes informations
      
                if(!empty($this->request->getData('submittedfile'))) // avatar envoyé
              {
      
                $avatar = $this->request->getData('submittedfile');
      
                  if($avatar->getError() == 0) // si pas d'erreur d'envoi
                {
      
                  $imageMimeTypes = array( // type MIME autorisé
                                          'image/jpg',
                                          'image/jpeg'
                                        );
      
                    if($avatar->getSize() > 3047171) // taille du fichier
                 {
                    return $this->response->withStringBody('sizenotok'); // fichier trop gros
                  }
      
                    if(!in_array($avatar->getClientMediaType(), $imageMimeTypes)) // test du type MIME
                  {
                    return $this->response->withStringBody('typenotok'); // type MIME incorrect
                  }
      
                // renommage du fichier
      
                  $name = $avatar->getClientFilename();
      
                  $name = $this->Authentication->getIdentity()->username . '.jpg';
      
                  $targetPath = 'users/'.$this->Authentication->getIdentity()->username.'/'. $name.'';
      
                // déplacement fichier
      
                  $avatar->moveTo($targetPath);
      
                }
      
              }
      
                $data = array(); // création d'un tableau contenant les valeurs modifiées
      
                // vérification description
      
                  if(!empty($this->request->getData('description'))) // description non vide
                {
      
                  $user->description = strip_tags($this->request->getData('description')); // suppression d'éventuelles balises
      
                  $user->description = AppController::linkify_content($user->description); // parsage
      
                  $data['description'] = $user->description; // stockage dans le tableau data
      
                }

              // vérification pays
      
                if(!empty($this->request->getData('pays'))) // pays non vide
              {
                $user->pays = strip_tags($this->request->getData('pays')); // suppression d'éventuelles balises
      
                $data['pays'] = $user->pays; // stockage dans le tableau data
              }
      
              // mot de passe
      
              if(!empty($this->request->getData('password')))
            {
                if($this->request->getData('password') != $this->request->getData('confirmpassword'))  // les deux mots de passe ne correspondent pas
              {
                return $this->response->withStringBody('notsamepassword'); // renvoi d'une réponse
              }

                elseif(!preg_match('/[A-Za-z0-9_~\-!@#\$%\^&\*\(\)]{8,20}$/', $this->request->getData('password')))
              {
                return $this->response->withStringBody('passwordformat'); // format du mot de passe
              }
                else
              {
                $data['password'] = $this->request->getData('password'); // stockage dans le tableau data
              }
      
            }
      
            // adresse mail

            if(!empty($this->request->getData('email'))) // si le champ mail n'est pas vide
          {
              if($this->request->getData('email') != $this->request->getData('confirmemail')) // les deux adresses mail ne correspondent pas
            {
              return $this->response->withStringBody('notsamemail'); // renvoi d'une réponse
            }
      
          // on vérifie que l'adresse mail n'est pas déjà utilisé
      
                $verif = $this->Users->find()
                                      ->select(['email'])
                                      ->where(['email' => $this->request->getData('email')]);
      
              if ($verif->isEmpty()) // si le mail n'existe pas
            {
              $data['email'] = $this->request->getData('email');
            }
              else
            {
              return $this->response->withStringBody('existingmail'); // adresse mail déjà utlisée
            }
          }
      
      // sauvegarde des données
      
                  $user = $this->Users->patchEntity($user, $data);
      
                  if ($this->Users->save($user))
                {
      
                    if(array_key_exists('password', $data)) // si le mot de passe à était modifié, on déconnecte l'utilisateur pour qu'il se reconnecte avec le nouveau mot de passe
                  {
      
                    $this->Flash->success('Mise à jour réussie de vos informations.Votre mot de passe ayant été changé, veuillez vous reconnecter avec celui-ci.');
      
                    $this->Authentication->logout();
      
                    return $this->response->withStringBody('updateokandpassword');
                  }
                    else
                  {
                    return $this->response->withStringBody('updateok'); // mise à jour réussie
                  }
      
                }
                  else
                {
                  return $this->response->withStringBody('probleme'); // echec ou pas de mise à jour
                }
              }
  
       }
        else // accès http -> définition du titre de la page + récupération des choix pour les notifications
       {
        $this->set('title', 'Paramètres - Youtux');

        $settings = $this->fetchTable('Settings')->find()
                                                  ->select([
                                                            'notif_comm',
                                                            'notif_follow'
                                                            ])
                                                  ->where(['username_settings' => $this->Authentication->getIdentity()->username]);

              foreach ($settings as $settings):

                  $notif_comm = $settings->notif_comm; // accepter ou non les notifications de nouveau commentaire

                  $notif_follow = $settings->notif_follow; // accepter ou non les notifications de nouvel abonnement

              endforeach;

//envoi des données à la vue

              $this->set('notif_comm', $notif_comm);

              $this->set('notif_follow', $notif_follow);
       }
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function deleteaccount()
    {

          $usertodelete = $this->Users->get($this->Authentication->getIdentity()->id); // récupération de l'entité à supprimer

          // suppression folder user

          $target = WWW_ROOT . 'users/'.$this->Authentication->getIdentity()->username.'';



          if ($this->removeFolder($target) AND $this->Users->delete($usertodelete))
        {
          $this->Flash->success('Compte supprimé avec succès.');
        }

          else
        {
          $this->Flash->error('Un problème technique empêche la suppression de votre compte.Veuillez réessayer plus tard.');
        }

        $this->Authentication->logout();

        return $this->redirect('/');

    }

        /**
     * Login method
     *
     * Connexion d'un utilisateur
     * 
     * @return \Cake\Http\Response|null|void Redirects to profil.
     */

        public function login()
    {
        
                if ($this->request->is('post'))
            {

                $result = $this->Authentication->getResult(); // récupération du résultat de l'authentification

                    if ($result->isValid()) // authentification réussie

                {

                    $user = $this->Authentication->getIdentity(); // on récupère l'identité du connecté

                    //  on récupère l'URL de provenance pour rediriger vers celle -ci après identification

                    $target = $this->Authentication->getLoginRedirect();

                        if (!$target) // je viens de la page d'accueil du site , je suis redirigé vers mon profil

                    {

                        $this->Flash->success(__('Bonjour '.h($this->request->getData('username')).'.'));

                        // redirection vers le nouveau profil

                        return $this->redirect('/'.$this->Authentication->getIdentity()->username.'');

                    }

                        else // je suis redirigé vers la page de provenance

                    {

                        return $this->redirect($target);

                    }

                }

                    //mot de passe /login incorrect

                    elseif(!$result->isValid())

                {

                    $this->Flash->error('Nom d\'utilisateur ou mot de passe incorrect.');

                }
            }
                else
            {

                $this->set('title' , 'Connexion requise | Youtux'); // titre de la page

            }

    }

     /**

     * Méthode Logout
     *
     * Déconnexion
     * @return \Cake\Http\Response|null|void Redirects to homepage.
     */

        public function logout()

    {

        $this->Flash->success('Vous avez été déconnecté.');

        $this->Authentication->logout();

        return $this->redirect('/');

    }

    // suppression du dossier utilisateur

      public function removeFolder($folderName) 
    {

      if (is_dir($folderName))

        $folderHandle = opendir($folderName);

        if (!$folderHandle)

           return false;

        while($file = readdir($folderHandle)) 
      {

            if ($file != "." && $file != "..") {

                 if (!is_dir($folderName."/".$file))

                      unlink($folderName."/".$file);

                 else

                      removeFolder($folderName.'/'.$file);

            }

      }

      closedir($folderHandle);

      rmdir($folderName);

      return true;

  } 
}

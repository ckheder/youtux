<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Datasource\ConnectionManager;

/**
 * Settings Controller
 *
 * @property \App\Model\Table\SettingsTable $Settings
 * @method \App\Model\Entity\Setting[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SettingsController extends AppController
{
    /**
      * Méthode Setupnotif
      *
      * Configuration des différentes notifications
      *
      */
      public function setupnotif()
      {
            if ($this->request->is('ajax')) // requête AJAX uniquement
          {

            $type_notif = $this->request->input('json_decode')->typenotif; // commentaire, follow

            $choix = $this->request->input('json_decode')->select; // oui ou non

            // requête de modification

            $statement = ConnectionManager::get('default')->prepare(
              'UPDATE settings SET '.$type_notif.' = :choix WHERE username_settings = :username');

              $statement->bindValue('choix', $choix, 'string');
              $statement->bindValue('username', $this->Authentication->getIdentity()->username, 'string');
              $statement->execute();

              $rowCount = $statement->rowCount();

                if ($rowCount == 1) // mise à jour réussie, renvoi d'une réponse
              {
                return $this->response->withStringBody('setupok');
              }
                elseif ($rowCount == 0) // echec mise à jour , renvoi d'une réponse
              {
                return $this->response->withStringBody('setupnotok');
              }
            }

            else // en cas de non requête AJAX on lève une exception 404
          {
            throw new NotFoundException(__('Cette page n\'existe pas.'));
          }

      }
}

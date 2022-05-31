<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Paginator');
        $this->Authentication->allowUnauthenticated(['display']); // on peut voir la page d'accueil en étant non authentifié

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

        /**
     * Méthode linkify_content
     *
     * Conversion des émoji vers image, # lien cliquable vers le moteur de recherche, URL vers lien cliquable
     *
     * Paramètre : $content -> description d'une vidéo, commentaires
     *
     * Sortie : $content -> contenu parsé
*/
        public function linkify_content($contenu)
    {

        $contenu =  preg_replace('/:([^\s]+):/', '<img src="/youtux/img/emoji/$1.png" alt=":$1:"/>', $contenu); // emoji

        //URL

        $pattern_link = '/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%\/\.\w\-_]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\\\\\\\w]*))?)/';

        $contenu = preg_replace($pattern_link, '<a href="$1" class="text-decoration-none" target="_blank">$1</a>', $contenu);

        $contenu =  preg_replace('/#([^\s]+)/','<a href="/youtux/search/hashtag/$1" class="text-decoration-none">#$1</a>',$contenu); //#something

        return $contenu;
    }

            /**
             * Méthode checkblock
             *
             * Vérifie si la personne à dont je consulte les vidéos
             *
             * Paramètres : $bloqueur -> le bloqueur , $bloque -> le bloque
             *
             * Sortie : oui | non
             *
             *
        */

          public function checkblock($bloqueur, $bloque)
        {
          $check_block  = $this->fetchTable('Block')->find()
                                                    ->where(['bloqueur' => $bloqueur, 
                                                              'bloque' => $bloque]);

          $result_block = $check_block->first();

              if (is_null($result_block)) // si pas de résultat, je ne suis pas bloqué
            {
              $resultblock = 'noblock';
            }
              else
            {
              $resultblock = 'block';
            }

            return $resultblock;
        }

        /**
         * Méthode check_notif
         *
         * Détermine si la personne veut ou pas des notifications, cas particulier des notification de message : on détermine si la conversation est masquée ou pas
         *
         * Paramètre : $notification -> type de notification : comm, abo, citation,... | $username -> profil sur qui faire le test | $conversation -> identifiant de conversation dans le cas d'une notification de message
         *
         * Sortie : $check_notif : oui | non
    */
    public function check_notif($notification, $username)
    {


        $check_notif = $this->fetchTable('Settings')->find()->select(['notif_'.$notification.''])->where(['username_settings' => $username]);

        $notif = 'notif_'.$notification.'';

            foreach ($check_notif as $check_notif)
          {
            $check_notif = $check_notif->$notif;
          }
      

      return $check_notif;

    }
}

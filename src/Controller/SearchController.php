<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Search Controller
 *
 * @method \App\Model\Entity\Search[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SearchController extends AppController
{

        public function initialize() : void
    {
        parent::initialize();
  
        $this->Authentication->allowUnauthenticated(['index','hashtag']); // on autorise les gens non auth à faire des recherches
    }
    /**
     * Index method
     * 
     * Recherche par mot-clé sur auteur, titre et description
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
        public function index()
    {

        $keyword = $this->request->GetParam('query'); // mot-clé pour la recherche

        $this->set('title', ''.$keyword.' - Youtux'); // titre de la page

        // on récupère toutes les informations du tweets contenant le mot clé ou si l'auteur est le mot clé

        $this->set('query_movie', $this->paginate($this->fetchTable('Movies')->find()->select([
                                                                                                'id_movie',
                                                                                                'filename',
                                                                                                'nb_vues',
                                                                                                'created',
                                                                                                'auteur',
                                                                                                'description',
                                                                                                'titre',
                                                                                            ])
                                                ->where(['MATCH (auteur, titre, description) AGAINST(:search)'])
                                                ->where(['type' => 'released']) // on ne cherche que les vidéos publiées
                                                ->bind(':search', $keyword)));
    }

    /**
     * Méthode hashtag
     *
     * Recherche par mot-clé avec # sur le titre ou la description 
     *
     */

        public function hashtag()
    {

        $keyword = preg_replace('/#([^\s]+)/','$1',$this->request->GetParam('query')); // suppression du # dans le mot clé

        $this->set('title', 'Hashtag '.$keyword.' - Youtux'); // titre de la page

        // on récupère toutes les informations du tweets contenant #mot-clé

        $this->set('query_movie_hashtag', $this->paginate($this->fetchTable('Movies')->find()->select([
                                                                                                        'id_movie',
                                                                                                        'filename',
                                                                                                        'nb_vues',
                                                                                                        'created',
                                                                                                        'auteur',
                                                                                                        'description',
                                                                                                        'titre',
                                                                                                        ])
                                        
                                        ->where([
                                            'OR' => ['titre REGEXP' => '#[[:<:]]'.$keyword.'[[:>:]]','description REGEXP' => '#[[:<:]]'.$keyword.'[[:>:]]']]
                                        )
                                        ->where(['type' => 'released']) // on ne cherche que les vidéos publiées
                                        ));


    }

}

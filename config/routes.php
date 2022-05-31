<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use Cake\Http\Middleware\CsrfProtectionMiddleware;

return static function (RouteBuilder $routes) {
    /*
     * The default class to use for all routes
     *
     * The following route classes are supplied with CakePHP and are appropriate
     * to set as the default:
     *
     * - Route
     * - InflectedRoute
     * - DashedRoute
     *
     * If no call is made to `Router::defaultRouteClass()`, the class used is
     * `Route` (`Cake\Routing\Route\Route`)
     *
     * Note that `Route` does not do any inflections on URLs which will result in
     * inconsistently cased URLs when used with `:plugin`, `:controller` and
     * `:action` markers.
     */
    /** @var \Cake\Routing\RouteBuilder $routes */
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder) {
        $builder->setExtensions(['json', 'xml']);
        // Register scoped middleware for in scopes.
        $builder->registerMiddleware('csrf', new CsrfProtectionMiddleware([
            'httponly' => true,
        ]));
    
        /*
         * Apply a middleware to the current route scope.
         * Requires middleware to be registered through `Application::routes()` with `registerMiddleware()`
         */
        
        /*
         * Here, we are connecting '/' (base path) to a controller called 'Pages',
         * its action called 'display', and we pass a param to select the view file
         * to use (in this case, templates/Pages/home.php)...
         */
        //$builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

        $builder->connect('/',['controller' => 'Movies', 'action' => 'home']);

        /*
         * ...and connect the rest of 'Pages' controller's URLs.
         */
        $builder->connect('/pages/*', 'Pages::display');

        /* Route Users */

         // route profil

        $builder->connect('/{username}/apropos',['controller' => 'Users', 'action' => 'view'],['_name' => 'profil'])->setPass(['username']);

         // route login

        $builder->connect('/login',['controller' => 'Users', 'action' => 'login']);

         // route logout

        $builder->connect('/logout',['controller' => 'Users', 'action' => 'logout']);

        // route inscription

        $builder->connect('/register', ['controller' => 'Users', 'action' => 'add']);

        // route settings

        $builder->connect('/settings', ['controller' => 'Users', 'action' => 'edit']);

        $builder->connect('/deletemyaccount', ['controller' => 'Users', 'action' => 'deleteaccount']);

        /* Route Vidéos */

         // ajouter une vidéo (vue)

        $builder->connect('/v/newvideo',['controller' => 'Movies', 'action' => 'add'],['_name' => 'newvideo']);

         // liste des vidéos par utilisateur (visite profil)

         $builder->connect('/{username}',['controller' => 'Movies', 'action' => 'index'],['_name' => 'userprofil'])->setPass(['username']);

        // liste des vidéos par utilisateur (modification)

        $builder->connect('/v/list',['controller' => 'Movies', 'action' => 'mymovies']);

        // voir une vidéo

        $builder->connect('/v/{idmovie}',['controller' => 'Movies', 'action' => 'view'])->setPass(['idmovie']);

        // publier une vidéo

        $builder->connect('/v/released',['controller' => 'Movies', 'action' => 'released']);

        // supprimer une vidéo

        $builder->connect('/v/delete',['controller' => 'Movies', 'action' => 'delete']);

        // supprimer toutes mes vidéos

        $builder->connect('/v/deletechannel',['controller' => 'Movies', 'action' => 'deletechannel']);

        // vidéo par catégorie

        $builder->connect('/v/channel/{channel}',['controller' => 'Movies', 'action' => 'categorie']);

        // vidéo non publiées

        $builder->connect('/v/unreleased',['controller' => 'Movies', 'action' => 'unreleasedmovie']);

        /* Route Vidéos Favorites */

            // voir les vidéos favorites

        $builder->connect('/v/favorite',['controller' => 'FavoriteMovies', 'action' => 'index']);

            // ajouter une vidéo favorite

        $builder->connect('/v/favorite/add',['controller' => 'FavoriteMovies', 'action' => 'add']);

            // supprimer une vidéo favorite

        $builder->connect('/v/favorite/delete',['controller' => 'FavoriteMovies', 'action' => 'delete']);

        /* Route Commentaire */

            // voir les commentaires

        $builder->connect('/comments/{idmovie}',['controller' => 'Comments', 'action' => 'view'])->setPass(['idmovie']);

            // ajouter un commentaire

        $builder->connect('/v/newcomment', ['controller' => 'Comments', 'action' => 'add']);

            // activer/désactiver les commentaires

        $builder->connect('/v/actioncomm',['controller' => 'Comments', 'action' => 'actioncomm']);

            // supprimer un commentaire

        $builder->connect('/v/deletecomm',['controller' => 'Comments', 'action' => 'delete']); 
        
            // modifier un commentaire

        $builder->connect('/v/updatecomm',['controller' => 'Comments', 'action' => 'edit']);

         /* Route Communaute */

         // Liste des messages communautaires par utilisateur(c-> community)

         $builder->connect('/{username}/communaute',['controller' => 'CommunityPosts', 'action' => 'index'])->setPass(['username']);

         // Affichage d'un message communautaire

         $builder->connect('/c/{idcommunitypost}',['controller' => 'CommunityPosts', 'action' => 'view'])->setPass(['idcommunitypost']);

         // Nouveau message communautaire

         $builder->connect('/c/new',['controller' => 'CommunityPosts', 'action' => 'add']);

        // supprimer un message communautaire

         $builder->connect('/c/delete',['controller' => 'CommunityPosts', 'action' => 'delete']); 
        
        // modifier un message commnunautaire

         $builder->connect('/c/update',['controller' => 'CommunityPosts', 'action' => 'edit']);

         /* Route commentaire de message communautaire */

         // voir les commentaires communautaires

         $builder->connect('/c/comments/{idcommunitypost}',['controller' => 'CommunityComments', 'action' => 'view'])->setPass(['idcommunitypost']);

         // ajouter un commentaire communautaire

         $builder->connect('/c/comments/newcommunitycomment',['controller' => 'CommunityComments', 'action' => 'add']);

         // supprimer un commentaire communautaire

         $builder->connect('/c/comments/deletecommunitycomment',['controller' => 'CommunityComments', 'action' => 'delete']);

         // modifier un commentaire communautaire

         $builder->connect('/c/comments/updatecommunitycomment',['controller' => 'CommunityComments', 'action' => 'edit']);

         /* Route abonnement */

         // Liste des abonnements(f -> follow)

         $builder->connect('/{username}/chaines',['controller' => 'Follows', 'action' => 'usersubscriptions'])->setPass(['username']);

         //actualités abonnements

         $builder->connect('/subscriptions',['controller' => 'Follows', 'action' => 'subscriptions']);

         // Ajouter un abonnement (via bouton)

         $builder->connect('/f/new',['controller' => 'Follows', 'action' => 'add']);

         // Supprimer un abonnement (via bouton)

         $builder->connect('/f/delete',['controller' => 'Follows', 'action' => 'delete']);

         // gestion abonnement

         $builder->connect('/f/list',['controller' => 'Follows', 'action' => 'index']);

         /* Notifications */

         // voir mes notifications

         $builder->connect('/n/i',['controller' => 'Notifications', 'action' => 'index']);

         // configurer mes notifications

        $builder->connect('/n/setup',['controller' => 'Settings', 'action' => 'setupnotif']);

         // supprimer une notification

         $builder->connect('/n/delete',['controller' => 'Notifications', 'action' => 'delete']); 

         /* Recherche */

         // recherche simple

         $builder->connect('/search/{query}', ['controller' => 'Search', 'action' => 'index']);

         // recherche avec hashtag

         $builder->connect('/search/hashtag/{query}', ['controller' => 'Search', 'action' => 'hashtag']);

         /* Blocage */

        // Liste des utilisateurs bloqués

        $builder->connect('/b/list',['controller' => 'Block', 'action' => 'index']);

        // Ajouter un abonnement (via bouton)

        $builder->connect('/b/new',['controller' => 'Block', 'action' => 'add']);

        // Supprimer un abonnement
         
        $builder->connect('/b/delete',['controller' => 'Block', 'action' => 'delete']);



        /*
         * Connect catchall routes for all controllers.
         *
         * The `fallbacks` method is a shortcut for
         *
         * ```
         * $builder->connect('/:controller', ['action' => 'index']);
         * $builder->connect('/:controller/:action/*', []);
         * ```
         *
         * You can remove these routes once you've connected the
         * routes you want in your application.
         */
        $builder->fallbacks();
    });

    /*
     * If you need a different set of middleware or none at all,
     * open new scope and define routes there.
     *
     * ```
     * $routes->scope('/api', function (RouteBuilder $builder) {
     *     // No $builder->applyMiddleware() here.
     *
     *     // Parse specified extensions from URLs
     *     // $builder->setExtensions(['json', 'xml']);
     *
     *     // Connect API actions here.
     * });
     * ```
     */
};

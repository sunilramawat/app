<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;
/**
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
 *
 */
Router::defaultRouteClass('DashedRoute');

Router::scope('/', function (RouteBuilder $routes) {
    $routes->extensions(['json','xml']);
    $routes->resources('Api');
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    //$routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
   // $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    $routes->connect('/', ['controller' => 'Index', 'action' => 'index', 'home']);
   //  $routes->connect('/', ['prefix' => 'admin','controller' => 'Users', 'action' => 'login']);
      
    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    Router::connect('/contact', 
        ['controller' => 'Pages', 'action' => 'index','contact']
    );

    Router::connect('/aboutus', 
        ['controller' => 'Pages', 'action' => 'index',"ABOUTUS"]
    );

    Router::connect('/learnmore', 
        ['controller' => 'Pages', 'action' => 'index',"LEARNMORE"]
    );

    Router::connect('/howitworks', 
        ['controller' => 'Pages', 'action' => 'index',"HOWITWORKS"]
    );

    Router::connect('/terms', 
        ['controller' => 'Pages', 'action' => 'index',"TERMS"]
    );

    Router::connect('/privacy_policy', 
        ['controller' => 'Pages', 'action' => 'index',"PRIVACYPOLICY"]
    );
    
     Router::connect('/register', 
        ['controller' => 'Users', 'action' => 'register']
    );
   
    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks('DashedRoute');
     Router::prefix('admin', function($routes) {
        // Because you are in the admin scope,
        // you do not need to include the /admin prefix
        // or the admin route element.
        $routes->connect('/', ['controller'=>'Users','action'=>'login']);
        $routes->fallbacks('InflectedRoute');
    });
});

/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();

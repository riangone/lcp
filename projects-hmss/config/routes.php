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
 *
 * @see          https://cakephp.org CakePHP(tm) Project
 *
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Routing\RouteBuilder;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\Router;

return function (RouteBuilder $routes) {
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
     * inconsistently cased URLs when used with `{plugin}`, `{controller}` and
     * `{action}` markers.
     */
    $routes->setRouteClass(DashedRoute::class);
    $domain = '/gdmz/cake';
    $routes->scope('/', function (RouteBuilder $builder) use ($domain) {

        /*
         * Here, we are connecting '/' (base path) to a controller called 'Pages',
         * its action called 'display', and we pass a param to select the view file
         * to use (in this case, templates/Pages/home.php)...
         */
        // $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);
        /*
         * ...and connect the rest of 'Pages' controller's URLs.
         */
        // $builder->connect('/pages/*', 'Pages::display');
        if (isset($_SERVER['REQUEST_URI'])) {
            $server = $_SERVER['REQUEST_URI'];
        } else {
            $server = $domain;
        }
        $url = str_replace($domain, '', $server);
        $arr_url = explode('/', $url);
        if (count($arr_url) > 1) {
            $sys_id = $arr_url[1];
            $session = Router::getRequest()->getSession();
            $login_user = $session->read('login_user');
            $session->write('sys_id', $sys_id);
            if ('' == $arr_url[1]) {
                $arr_url[1] = 'Master';
            } else {
                if ($sys_id != 'Master' && $sys_id != 'Login') {
                    if ($login_user == null || $login_user == '') {
                        $arr_url[1] = "Login";
                        $arr_url[2] = "Login";
                        $arr_url[3] = "donothing";
                    }
                }
            }
        }

        if (count($arr_url) > 1 && ('R4G' == $arr_url[1] || 'R4K' == $arr_url[1] || 'KRSS' == $arr_url[1])) {
            $arr_url[1] = 'R4/' . $arr_url[1];
        }

        if (2 == count($arr_url)) {
            $builder->connect(
                $url,
                [
                    'prefix' => $arr_url[1],
                    'controller' => $arr_url[1],
                    'action' => 'index',
                ]
            );
        } elseif (3 == count($arr_url)) {
            $builder->connect(
                $url,
                [
                    'prefix' => $arr_url[1],
                    'controller' => $arr_url[2],
                    'action' => 'index',
                ]
            );
        } elseif (count($arr_url) > 3) {
            $builder->connect(
                $url,
                [
                    'prefix' => $arr_url[1],
                    'controller' => $arr_url[2],
                    'action' => $arr_url[3],
                ]
            );
        }
        /*
         * Connect catchall routes for all controllers.
         *
         * The `fallbacks` method is a shortcut for
         *
         * ```
         * $builder->connect('/{controller}', ['action' => 'index']);
         * $builder->connect('/{controller}/{action}/*', []);
         * ```
         *
         * You can remove these routes once you've connected the
         * routes you want in your application.
         */
        $builder->fallbacks();
    });
    // $routes->connect('/master', ['prefix' => 'Master', 'controller' => 'Master', 'action' => 'index']);

    // $domain = '/gdmz/cake';
    // $routes->scope($domain, function (RouteBuilder $builder) use ($domain) {
    //     if (isset($_SERVER['REQUEST_URI'])) {
    //         $server = $_SERVER['REQUEST_URI'];
    //     } else {
    //         $server = $domain;
    //     }
    //     $url = str_replace($domain, '', $server);
    //     $arr_url = explode('/', $url);
    //     if (count($arr_url) > 1) {
    //         $sys_id = $arr_url[1];
    //         $session = Router::getRequest()->getSession();
    //         $login_user = $session->read('login_user');
    //         $session->write('sys_id', $sys_id);
    //         if ('' == $arr_url[1]) {
    //             $arr_url[1] = 'Master';
    //         } else {
    //             if ($sys_id != 'Master' && $sys_id != 'Login') {
    //                 if ($login_user == null || $login_user == '') {
    //                     $arr_url[1] = "Login";
    //                     $arr_url[2] = "Login";
    //                     $arr_url[3] = "donothing";
    //                 }
    //             }
    //         }
    //     }

    //     if (count($arr_url) > 1 && ('R4G' == $arr_url[1] || 'R4K' == $arr_url[1] || 'KRSS' == $arr_url[1])) {
    //         $arr_url[1] = 'R4/' . $arr_url[1];
    //     }

    //     if (2 == count($arr_url)) {
    //         $builder->connect(
    //             $url,
    //             [
    //                 'prefix' => $arr_url[1],
    //                 'controller' => $arr_url[1],
    //                 'action' => 'index',
    //             ]
    //         );
    //     } elseif (3 == count($arr_url)) {
    //         $builder->connect(
    //             $url,
    //             [
    //                 'prefix' => $arr_url[1],
    //                 'controller' => $arr_url[2],
    //                 'action' => 'index',
    //             ]
    //         );
    //     } elseif (count($arr_url) > 3) {
    //         $builder->connect(
    //             $url,
    //             [
    //                 'prefix' => $arr_url[1],
    //                 'controller' => $arr_url[2],
    //                 'action' => $arr_url[3],
    //             ]
    //         );
    //     }

    //     $builder->fallbacks();
    // });
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

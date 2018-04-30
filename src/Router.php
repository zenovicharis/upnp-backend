<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 30.4.18
 * Time: 13:01
 */

namespace Upnp;
use Cicada\Routing\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Upnp\Application;
use Symfony\Component\HttpFoundation\Request;

class Router extends \Cicada\Routing\Router
{
    private $routes = [];


    public function route(\Cicada\Application $app, Request $request)
    {
        $url = $request->getPathInfo();
        $method = $request->getMethod();

        /** @var $route Route */
        foreach ($this->routes as $route) {

            // Match by method
            if ($route->getMethod() == $method) {

                // Match by URL
                $matches = $route->matches($url);
                if ($matches !== false) {

                    // Emit match event
                    $app['emitter']->emit(self::EVENT_MATCH, [$app, $request, $route]);

                    // Execute the route
                    return $route->run($app, $request, $matches);
                }
            }
        }

        // Emit no_match event
        $app['emitter']->emit(self::EVENT_NO_MATCH, [$app, $request]);

        // Return HTTP 404
        return new RedirectResponse('/error');
    }

}
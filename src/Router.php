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
use Symfony\Component\HttpFoundation\Response;
use Upnp\Application;
use Symfony\Component\HttpFoundation\Request;

class Router extends \Cicada\Routing\Router
{
    private $routes = [];


    public function route(\Cicada\Application $app, Request $request)
    {

        $response = parent::route($app, $request);
        if ($response->getStatusCode() == Response::HTTP_NOT_FOUND) {
            // Return HTTP 404
            return new RedirectResponse('/error');
        }
        return $response;
    }

}
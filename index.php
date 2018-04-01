<?php
// enable Error printing
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Upnp\Application;
use Upnp\Controllers\MainController;

$app = new Application($_SERVER['HOME']);


$mainController = new MainController($app['newsService'], $app['userService'], $app['imgur'], $app['twig']);
$middleware = $app['middleware'];

/** @var RouteCollection $newsRouteCollection */
$newsRouteCollection = $app['collection_factory']->prefix('/news')->before(
    function(Application $app, Request $request) use ($middleware){
        if(!$middleware->isLoggedIn()){
            $continue = $request->getPathInfo();
            return new RedirectResponse('/login?continue='.$continue);
        }
    });


//News CRUD
$newsRouteCollection->post('/create',       [$mainController, "createNews"]); //done create news
$newsRouteCollection->post('/update/{id}',  [$mainController, "updateNews"]); // done   update news
$newsRouteCollection->post('/delete/{id}',  [$mainController, "deleteNews"]); // done   delete news
$newsRouteCollection->get('/create',        [$mainController, "create"]); //done create news
$newsRouteCollection->get('/',              [$mainController, "getNews"]); // done  read all news
$newsRouteCollection->get('/edit/{id}',     [$mainController, "editNews"]); //done   get one news
$newsRouteCollection->get('/{id}',          [$mainController, "singleNews"]); // done show only one news


$app->post('/volountieer/create', [$mainController, "CreateVolountieer"]); // done create volountieer
$app->get('/volountieers', [$mainController, "getVolountieers"]); //   done read all volountieer JSON

$app->get('/dashboard',     [$mainController, "dashboard"]);
$app->get('/update-news',   [$mainController, "update"]);

$app->get('/update-news',   [$mainController, "update"]);
$app->get('/login',         [$mainController, "login"]);
$app->get('/logout',        [$mainController, "logout"]);
$app->post('/login',        [$mainController, "loginValidate"])->before(
    function(Application $app, Request $request) use ($middleware){
        $user = $middleware->checkCredentials($app, $request);
        if(empty($user)){
            $continue = $request->getPathInfo();
            return new RedirectResponse('/login?continue='.$continue);
        }
        $request->request->set('user', $user);
    });

$app->addRouteCollection($newsRouteCollection);

$app->run();
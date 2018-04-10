<?php
// enable Error printing
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
use Upnp\Application;
use Upnp\Controllers\MainController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Cicada\Routing\RouteCollection;

$app = new Application($_SERVER['HOME']);



$mainController = new MainController($app['newsService'], $app['userService'], $app['volountieerService'], $app['imgur'], $app['twig'], $app['validationLibrary'], $app['albumService']);
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
$newsRouteCollection->post('/create',       [$mainController, "createNews"]);
$newsRouteCollection->post('/update/{id}',  [$mainController, "updateNews"]);
$newsRouteCollection->post('/delete/{id}',  [$mainController, "deleteNews"]);
$newsRouteCollection->get('/create',        [$mainController, "create"]);
$newsRouteCollection->get('/',              [$mainController, "getNews"]);
$newsRouteCollection->get('/edit/{id}',     [$mainController, "editNews"]);
$newsRouteCollection->get('/{id}',          [$mainController, "singleNews"]);
//$newsRouteCollection->get('/dashboard',         [$mainController, "dashboard"]);


$app->post('/volountieer/create', [$mainController, "CreateVolountieer"]);
$app->get('/volountieers',        [$mainController, "getVolountieers"]);


$app->post('/image/delete/{id}',  [$mainController, "deleteImage"]);

$app->get('/dashboard',     [$mainController, "dashboard"]);
$app->get('/logout',        [$mainController, "logout"]);
$app->get('/login',         [$mainController, "login"]);
$app->post('/login',        [$mainController, "loginValidate"])->before(
    function(Application $app, Request $request) use ($middleware){

        $user = $middleware->checkCredentials($app, $request);

        if(empty($user)){
            $continue = $request->getPathInfo();
            return new RedirectResponse('/login?continue='.$continue);
        }
        $request->request->set('user', $user);
    });
// albums routes
$app->get('/album/create', [$mainController, 'createAlbum']);
$app->post('/album/create', [$mainController, 'createAlbumPost']);
$app->get('/album/info', [$mainController, 'infoAlbum']);
$app->get('/album/edit', [$mainController, 'editAlbum']);
$app->get('/album/albums', [$mainController, 'albums']);

$app->addRouteCollection($newsRouteCollection);
$app->run();
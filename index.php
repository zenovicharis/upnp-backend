<?php
// enable Error printing
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
use Upnp\Application;
use Upnp\Controllers\MainController;
use Upnp\Controllers\PublicController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Cicada\Routing\RouteCollection;

$app = new Application($_SERVER['HOME']);


$publicController = new PublicController($app['publicService']);


$mainController = new MainController($app['newsService'], $app['userService'], $app['volountieerService'], $app['imgur'], $app['twig'], $app['validationLibrary'], $app['albumService']);
$middleware = $app['middleware'];

/** @var RouteCollection $newsRouteCollection */
$newsRouteCollection = $app['collection_factory']->prefix('/news')->before(
    function (Application $app, Request $request) use ($middleware) {
        if (!$middleware->isLoggedIn()) {
            $continue = $request->getPathInfo();
            return new RedirectResponse('/login?continue=' . $continue);
        }
    });

/** @var RouteCollection $albumRouteCollection */
$albumRouteCollection = $app['collection_factory']->prefix('/album')->before(
    function (Application $app, Request $request) use ($middleware) {
        if (!$middleware->isLoggedIn()) {
            $continue = $request->getPathInfo();
            return new RedirectResponse('/login?continue=' . $continue);
        }
    });

// Albums routes
$albumRouteCollection->post('/create', [$mainController, 'createAlbumPost']);
$albumRouteCollection->get('/create', [$mainController, 'createAlbum']);
$albumRouteCollection->get('/info/{id}', [$mainController, 'infoAlbum']);
$albumRouteCollection->get('/edit/{id}', [$mainController, 'editAlbum']);
$albumRouteCollection->get('/albums', [$mainController, 'albums']);
$albumRouteCollection->delete('/image/{id}', [$mainController, 'deleteAlbumImage']);
$albumRouteCollection->post('/update/{id}', [$mainController, 'updateAlbum']);
$albumRouteCollection->post('/upload/{id}', [$mainController, 'uploadImageToAlbum']);


// News routes
$newsRouteCollection->post('/create', [$mainController, "createNews"]);
$newsRouteCollection->post('/update/{id}', [$mainController, "updateNews"]);
$newsRouteCollection->post('/delete/{id}', [$mainController, "deleteNews"]);
$newsRouteCollection->get('/news', [$mainController, "dashboard"]);
$newsRouteCollection->get('/create', [$mainController, "create"]);
$newsRouteCollection->get('/edit/{id}', [$mainController, "editNews"]);
$newsRouteCollection->get('/{id}', [$mainController, "singleNews"]);

// Volountieer routes
$app->post('/volountieer/create', [$mainController, "CreateVolountieer"]);
$app->get('/volountieers', [$mainController, "getVolountieers"]);



$app->post('/image/delete/{id}',  [$mainController, "deleteImage"]);

$app->get('/news', [$publicController, "getNews"]);
$app->get('/albums', [$publicController, "getAlbums"]);


//$app->get('/dashboard',     [$mainController, "dashboard"]);
$app->get('/logout',        [$mainController, "logout"]);
$app->get('/login',         [$mainController, "login"]);
$app->post('/login',        [$mainController, "loginValidate"])->before(
    function(Application $app, Request $request) use ($middleware){

        $user = $middleware->checkCredentials($app, $request);

        if (empty($user)) {
            $continue = $request->getPathInfo();
            return new RedirectResponse('/login?continue=' . $continue);
        }
        $request->request->set('user', $user);
    });

$app->addRouteCollection($newsRouteCollection);
$app->addRouteCollection($albumRouteCollection);
$app->run();
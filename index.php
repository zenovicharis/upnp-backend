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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Cicada\Routing\RouteCollection;

$app = new Application($_SERVER['HOME']);

// Controllers
$publicController = new PublicController( $app['twig'],$app['publicService']);
$mainController = new MainController($app['newsService'], $app['userService'], $app['volountieerService'], $app['imgur'], $app['twig'], $app['validationLibrary'], $app['albumService'],$app['imageService']);
$middleware = $app['middleware'];


// Route Collections
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

/** @var RouteCollection $volunteerRouteCollection */
$volunteerRouteCollection = $app['collection_factory']->prefix('/volonteri')->before(
    function (Application $app, Request $request) use ($middleware) {
        if (!$middleware->isLoggedIn()) {
            $continue = $request->getPathInfo();
            return new RedirectResponse('/login?continue=' . $continue);
        }
    });

/** @var RouteCollection $englishRouteCollection */
$englishRouteCollection = $app['collection_factory']->prefix('/en');


//Routes
$app->get('/logout',                            [$mainController, "logout"]);
$app->get('/login',                             [$mainController, "login"]);
$app->post('/login',                            [$mainController, "loginValidate"])->before(
    function (Application $app, Request $request) use ($middleware) {

        $user = $middleware->checkCredentials($app, $request);

        if (empty($user)) {
            $continue = $request->getPathInfo();
            return new RedirectResponse('/login?continue=' . $continue);
        }
        $request->request->set('user', $user);
    });

$volunteerRouteCollection->get('',              [$mainController, 'getAllVolunteer']);
$volunteerRouteCollection->get('/{id}',         [$mainController, 'getVolunteer']);

// Albums routes
$albumRouteCollection->post('/create',          [$mainController, 'createAlbumPost']);
$albumRouteCollection->get('/create',           [$mainController, 'createAlbum']);
$albumRouteCollection->get('/info/{id}',        [$mainController, 'infoAlbum']);
$albumRouteCollection->get('/edit/{id}',        [$mainController, 'editAlbum']);
$albumRouteCollection->get('',                  [$mainController, 'albums']);
$albumRouteCollection->post('/image/{id}',      [$mainController, 'deleteAlbumImage']);
$albumRouteCollection->post('/update/{id}',     [$mainController, 'updateAlbum']);
$albumRouteCollection->post('/upload/{id}',     [$mainController, 'uploadImageToAlbum']);
$albumRouteCollection->post('/delete/{id}',     [$mainController, 'deleteAlbum']);


// News routes
$newsRouteCollection->post('/create',           [$mainController, "createNews"]);
$newsRouteCollection->post('/update/{id}',      [$mainController, "updateNews"]);
$newsRouteCollection->post('/delete/{id}',      [$mainController, "deleteNews"]);
$newsRouteCollection->post('/image/{id}',       [$mainController, "changeNewsImage"]);

$newsRouteCollection->get('',                   [$mainController, "news"]);
$newsRouteCollection->get('/create',            [$mainController, "getCreateNews"]);
$newsRouteCollection->get('/edit/{id}',         [$mainController, "editNews"]);
$newsRouteCollection->get('/{id}',              [$mainController, "singleNews"]);

// Volountieer routes
$app->post('/volountieer/create',             [$mainController, "CreateVolountieer"]);
$app->get('/volountieers',                    [$mainController, "getVolountieers"]);


$app->post('/image/delete/{id}',              [$mainController, "deleteImage"]);

$app->get('/api/news/{lang}',                 [$publicController, "getNews"]);
$app->get('/api/projects/{lang}',             [$publicController, "getProjects"]);
$app->get('/api/albums',                      [$publicController, "getAlbums"]);

$app->get('',                                 [$publicController, "landing"]);
$app->get('/',                                [$publicController, "landing"]);
$app->get('/volunteer',                       [$publicController, "volunteer"]);
$app->get('/gallery',                         [$publicController, "gallery"]);
$app->get('/contact',                         [$publicController, "contact"]);
$app->get('/patreon',                         [$publicController, "patreon"]);
$app->get('/aboutus',                         [$publicController, "aboutus"]);
$app->get('/public/news',                     [$publicController, "news"]);
$app->get('/public/news/{id}',                [$publicController, "getSingleNews"]);
$app->get('/public/projects',                 [$publicController, "projects"]);
$app->get('/projects',                        [$mainController, "projects"]);


$englishRouteCollection->get('',                [$publicController, "landingEn"]);
$englishRouteCollection->get('/',               [$publicController, "landingEn"]);
$englishRouteCollection->get('/volunteer',      [$publicController, "volunteerEn"]);
$englishRouteCollection->get('/public/news',    [$publicController, "newsEn"]);
$englishRouteCollection->get('/public/projects',[$publicController, "projectEn"]);
$englishRouteCollection->get('/public/news/{id}',[$publicController, "getSingleNewsEn"]);
$englishRouteCollection->get('/gallery',        [$publicController, "galleryEn"]);
$englishRouteCollection->get('/contact',        [$publicController, "contactEn"]);
$englishRouteCollection->get('/patreon',        [$publicController, "patreonEn"]);
$englishRouteCollection->get('/aboutus',        [$publicController, "aboutusEn"]);


$app->addRouteCollection($newsRouteCollection);
$app->addRouteCollection($albumRouteCollection);
$app->addRouteCollection($englishRouteCollection);
$app->addRouteCollection($volunteerRouteCollection);
$app->run();
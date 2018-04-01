<?php
// enable Error printing
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Upnp\Application;
use Upnp\Controllers\MainController;

$app = new Application($_SERVER['HOME']);


$mainController = new MainController($app['newsService'], $app['twig']);

//News CRUD
$app->post('/news/create',      [$mainController, "createNews"]); //done create news
$app->get('/news',              [$mainController, "getNews"]); // done  read all news
$app->post('/news/update/{id}', [$mainController, "updateNews"]); // done   update news
$app->post('/news/delete/{id}', [$mainController, "deleteNews"]); // done   delete news
$app->get('/news/{id}',         [$mainController, "getNewsById"]); //done   get one news
$app->get('/single-news/{id}', [$mainController, "singleNew"]); // done show only one news


$app->post('/volountieer/create', [$mainController, "CreateVolountieer"]); // done create volountieer
$app->get('/volountieers', [$mainController, "getVolountieers"]); //   done read all volountieer JSON

$app->get('/dashboard', [$mainController, "dashboard"]);
$app->get('/create-news', [$mainController, "create"]);
$app->get('/update-news', [$mainController, "update"]);
$app->get('/login', [$mainController, "login"]);


$app->run();
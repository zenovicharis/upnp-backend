<?php

namespace Upnp;

use Twig_SimpleFunction;
use Upnp\Services\AlbumService;
use Upnp\Services\NewsService;
use Upnp\Services\UserService;
use Upnp\Clients\ImgurClient;
use Upnp\Middleware\Authentication;
use Upnp\Libraries\ValidationLibrary;
use Upnp\Services\VolountieerService;
use Illuminate\Database\Capsule\Manager as Capsule;

class Application extends \Cicada\Application
{
    /**
     * Application constructor.
     * @param $configPath
     */
    public function __construct($configPath){
        parent::__construct();
        $this->configure($configPath);
        $this->setupLibraries();
//        $this->configureDatabase();
        $this->configureEloquentDatabase();
        $this->configureMiddleware();
        $this->configureClients();
        $this->setUpServices();
        $this->setupTwig();
    }

    protected function configure($configPath) {
        $this['config'] = function () use ($configPath) {
            return new Configuration($configPath);
        };
    }

    protected function configureMiddleware() {
        $this['middleware'] = function () {
            return new Authentication();
        };
    }

    protected function setupLibraries(){
        $this['validationLibrary'] = function () {
            return new ValidationLibrary();
        };
    }

    private function setUpServices(){
        $imgurClient = $this['imgur'];
        $this['newsService'] = function() use ($imgurClient) {
            return new NewsService($imgurClient);
        };

        $this['userService'] = function(){
            return new UserService();
        };

        $this['volountieerService'] = function(){
            return new VolountieerService();
        };
      
        $this['albumService'] = function(){
            return new AlbumService();
        };
    }

    protected function configureClients() {
        $imgurConfig = $this['config']->getImgurClientConfig();
        $this['imgur'] = function () use ($imgurConfig) {
            return new ImgurClient($imgurConfig);
        };
    }
    protected function configureDatabase()
    {
        $dbConfig = $this['config']->getDbConfig();
        \ActiveRecord\Config::initialize(function (\ActiveRecord\Config $cfg) use ($dbConfig) {
            $cfg->set_model_directory('src/Models');
            $cfg->set_connections([
                'main' => sprintf('mysql://%s:%s@%s/%s',
                    $dbConfig['user'], $dbConfig['password'], $dbConfig['host'], $dbConfig['name']
                )
            ]);
            $cfg->set_default_connection('main');
        });
    }

    protected function configureEloquentDatabase()
    {
        $dbConfig = $this['config']->getDbConfig();
        $capsule = new Capsule;
        $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => $dbConfig['host'],
        'database'  => $dbConfig['name'],
        'username'  => $dbConfig['user'],
        'password'  => $dbConfig['password'],
        'charset'   => 'utf8',
        'collation' => 'utf8_general_ci',
        'prefix'    => '',
        ]);
        $capsule->bootEloquent();
    }


    private function setupTwig() {
        $this['twig'] = function() {
            $loader = new \Twig_Loader_Filesystem('public');
            $twig = new  \Twig_Environment($loader, array(//
//                'cache' => 'cache',
                'debug' => true
            ));
            $twig->addExtension(new \Twig_Extension_Debug());
            $pathFunction = function ($name, $params = []) {
                /** @var Route $route */
                $route = $this['router']->getRoute($name);
                return $route->getRealPath($params);
            };
            $twig->addFunction(new Twig_SimpleFunction('path', $pathFunction));

            return $twig;
        };
    }
}
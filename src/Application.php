<?php

namespace Upnp;

use Upnp\Router;
use Symfony\Component\HttpFoundation\Request;
use Twig_SimpleFunction;
use Upnp\Libraries\ExceptionHandler;
use Upnp\Services\AlbumService;
use Upnp\Services\MailService;
use Upnp\Services\NewsService;
use Upnp\Services\PublicService;
use Upnp\Services\UserService;
use Upnp\Services\ImageService;
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
    public function __construct($configPath)
    {
        parent::__construct();
        $this->configure($configPath);
        $this->configureMiddleware();
        $this->configureDatabase();
        $this->configureClients();
        $this->setupLibraries();
        $this->setUpServices();
        $this->setUpRouter();
        $this->setupTwig();

    }

    protected function configure($configPath)
    {
        $this['config'] = function () use ($configPath) {
            return new Configuration($configPath);
        };
    }

    protected function configureMiddleware()
    {
        $this['middleware'] = function () {
            return new Authentication();
        };
    }

    protected function setupLibraries()
    {
        $this['validationLibrary'] = function () {
            return new ValidationLibrary();
        };
    }

    private function setUpServices()
    {
        $imgurClient = $this['imgur'];
        $this['imageService'] = function () use ($imgurClient) {
            return new ImageService($imgurClient);
        };

        $this['newsService'] = function () {
            return new NewsService();
        };

        $this['userService'] = function () {
            return new UserService();
        };

        $this['volountieerService'] = function () {
            return new VolountieerService();
        };

        $this['albumService'] = function () {
            return new AlbumService();
        };

        $this['publicService'] = function () {
            return new PublicService();
        };

        $mailCredentials = $this['config']->getMailCredentials();
        $this['mailService'] = function () use ($mailCredentials) {
            return new MailService($mailCredentials);
        };
    }

    protected function configureClients()
    {
        $imgurConfig = $this['config']->getImgurClientConfig();
        $this['imgur'] = function () use ($imgurConfig) {
            return new ImgurClient($imgurConfig);
        };
    }


    protected function configureDatabase()
    {
        $dbConfig = $this['config']->getDbConfig();
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => $dbConfig['host'],
            'database' => $dbConfig['name'],
            'username' => $dbConfig['user'],
            'password' => $dbConfig['password'],
            'charset' => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix' => '',
        ]);
        $capsule->bootEloquent();
    }

    private function setupTwig()
    {
        $this['twig'] = function () {
            $loader = new \Twig_Loader_Filesystem('dist');
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

    private function setUpRouter()
    {
        $this['router'] = function () {
            return new Router();
        };
    }
}
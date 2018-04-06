<?php

namespace Upnp;

use Twig_SimpleFunction;
use Upnp\Clients\ImgurClient;
use Upnp\Services\NewsService;
use Upnp\Services\UserService;
use Upnp\Middleware\Authentication;

class Application extends \Cicada\Application
{
    /**
     * Application constructor.
     * @param $configPath
     */
    public function __construct($configPath){
        parent::__construct();
        $this->configure($configPath);

        $this->configureDatabase();
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

    private function setUpServices(){
        $this['newsService'] = function(){
            return new NewsService();
        };

        $this['userService'] = function(){
            return new UserService();
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
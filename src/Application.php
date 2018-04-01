<?php

namespace Upnp;

use Twig_SimpleFunction;
use Upnp\Services\NewsService;

class Application extends \Cicada\Application
{
    public function __construct($configPath){
        parent::__construct();
        $this->configure($configPath);

        $this->configureDatabase();
        $this->setUpServices();
        $this->setupTwig();
    }

    protected function configure($configPath) {
        $this['config'] = function () use ($configPath) {
            return new Configuration($configPath);
        };
    }

    private function setUpServices(){
        $this['newsService'] = function(){
            return new NewsService();
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
            $loader = new \Twig_Loader_Filesystem('front-end');
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
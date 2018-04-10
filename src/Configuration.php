<?php

namespace Upnp;

class Configuration
{

    private $fullConfigPath;
    private $config;

    public function __construct($configPath)
    {
        $this->fullConfigPath = $configPath . "/config.json";
        $this->config = json_decode(file_get_contents($this->fullConfigPath), true);
    }

    public function getDbConfig()
    {
        return $this->config['database'];
    }

    public function getImgurClientConfig()
    {
        return $this->config['imgur'];
    }
}
<?php

namespace Framework\Service;

use Framework\Exception\FrameworkException;
use Framework\ServiceInterface;

class Config implements ServiceInterface
{
    private const CONFIG_FILE = CONFIG_DIR . '/config.php';

    /** array */
    private $config;

    /**
     * Config constructor.
     * @throws FrameworkException
     */
    public function __construct()
    {
        if (!file_exists(self::CONFIG_FILE)) {
            throw new FrameworkException('Unable to find config file');
        }

        $this->config = require(self::CONFIG_FILE);
    }

    /**
     * @param string $configName
     * @return mixed
     * @throws FrameworkException
     */
    public function get(string $configName)
    {
        if (!array_key_exists($configName, $this->config)) {
            throw new FrameworkException('Unable to get config value ' . $configName);
        }

        return $this->config[$configName];
    }
}
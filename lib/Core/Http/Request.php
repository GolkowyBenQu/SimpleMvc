<?php

namespace Framework\Http;

use Framework\DependencyContainer as DI;
use Framework\Service\Router;

class Request
{
    /** @var Request */
    private static $instance;
    /** @var array */
    private $params;

    private function __construct()
    {
        $this->extractData($_GET);
        $this->extractData($_POST);
    }

    /**
     * @return Request
     * @throws \Exception
     */
    public static function createFromGlobals(): Request
    {
        self::$instance = new self();

        /** @var Router $router */
        $router = DI::get(Router::class);

        $route = $router->resolve($_SERVER['REQUEST_URI']);

        if ($route) {
            self::$instance->extractRoute($route, $_SERVER['REQUEST_URI']);
        }

        return self::$instance;
    }

    /**
     * @param string $paramName
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getParam(string $paramName, $defaultValue = false)
    {
        if ($this->params && !array_key_exists($paramName, $this->params)) {
            return $defaultValue;
        }

        return $this->params[$paramName];
    }

    /**
     * @param array $paramsBag
     */
    private function extractData(array $paramsBag): void
    {
        foreach ($paramsBag as $paramName => $paramValue) {
            $this->setParam($paramName, $paramValue);
        }
    }

    /**
     * @param array $route
     * @param string $requestUri
     */
    private function extractRoute(array $route, string $requestUri): void
    {
        if (isset($route['controller'])) {
            $this->setParam('controller', $route['controller']);
        }

        if (isset($route['action'])) {
            $this->setParam('action', $route['action']);
        }

        if (!isset($route['params'])) {
            return;
        }

        $requestUriParams = explode('/', $requestUri);
        $requestUriParams = array_slice($requestUriParams, 3);

        foreach ($requestUriParams as $index => $param) {
            if (isset($route['params'][$index])) {
                $this->setParam($route['params'][$index], $requestUriParams[$index]);
            }
        }
    }

    /**
     * @param string $paramName
     * @param mixed $paramValue
     */
    private function setParam(string $paramName, $paramValue): void
    {
        $this->params[$paramName] = $paramValue;
    }
}
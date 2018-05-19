<?php

namespace Framework\Service;

use Framework\Exception\FrameworkException;
use Framework\ServiceInterface;

class Router implements ServiceInterface
{
    private const ROUTING_FILE = CONFIG_DIR . '/routing.php';

    /** @var array */
    private $routes;

    /**
     * Router constructor.
     * @throws FrameworkException
     */
    public function __construct()
    {
        if (!file_exists(self::ROUTING_FILE)) {
            throw new FrameworkException('Unable to find routing file');
        }

        $this->routes = require(self::ROUTING_FILE);
    }

    /**
     * @param string $uri
     * @return array
     */
    public function resolve(string $uri): array
    {
        foreach ($this->routes as $routeParams) {
            if ($this->matchRoute($uri, $routeParams)) {
                return $routeParams;
            }
        }

        return [];
    }

    /**
     * @param string $uri
     * @param array $routeParams
     * @return bool
     */
    public function matchRoute(string $uri, array $routeParams): bool
    {
        if (!isset($routeParams['uri'])) {
            return false;
        }

        $requestParts = explode('/', $uri);

        if (!isset($requestParts[1]) || strtolower($requestParts[1]) !== strtolower($routeParams['controller'])) {
            return false;
        }

        if (!isset($requestParts[2]) || strtolower($requestParts[2]) !== strtolower($routeParams['action'])) {
            return false;
        }

        $requestUriParams = explode('/', $uri);
        $requestUriParams = array_slice($requestUriParams, 3);

        if (!isset($routeParams['params'])) {
            $routeParams['params'] = [];
        }

        if (count($routeParams['params']) !== count($requestUriParams)) {
            return false;
        }

        return true;
    }

    public function redirectToRoute(string $name)
    {

    }

    public function redirectToUrl(string $url)
    {

    }

    /**
     * @param string $routeName
     * @return string
     * @throws FrameworkException
     */
    public function generateUrl(string $routeName): string
    {
        if (!isset($this->routes)) {
            throw new FrameworkException('Unable to find given route name ' . $routeName);
        }

        $route = $this->routes[$routeName];

        if (!isset($route['uri'])) {
            throw new FrameworkException('Unable to generate URL');
        }

        return $route['uri'];
    }

    private function redirect($location)
    {

    }
}
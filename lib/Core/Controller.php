<?php

namespace Framework;

use Framework\Exception\FrameworkException;
use Framework\DependencyContainer as DI;
use Framework\Http\Request;
use Framework\Service\Router;

class Controller
{
    /** @var Request */
    private $request;

    /**
     * Controller constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param string $serviceName
     * @return ServiceInterface
     * @throws \Exception
     */
    public function getService(string $serviceName): ServiceInterface
    {
        return DI::get($serviceName);
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param string $viewName
     * @return string
     * @throws FrameworkException
     */
    public function render(string $viewName)
    {
        $viewPath = VIEW_DIR . '/' . $viewName . '.php';

        if (!file_exists($viewPath)) {
            throw new FrameworkException('Unable to find view file ' . $viewPath);
        }

        require($viewPath);
    }

    /**
     * @param string $routeName
     * @throws FrameworkException
     */
    public function generateUrl(string $routeName)
    {
        /** @var Router $router */
        $router = DI::get(Router::class);
        return $router->generateUrl($routeName);
    }
}
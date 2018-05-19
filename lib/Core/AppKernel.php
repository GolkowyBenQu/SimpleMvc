<?php

namespace Framework;

use Framework\Exception\FrameworkException;
use Framework\Http\Request;
use Framework\DependencyContainer as DI;
use Framework\Http\Response;
use Framework\Service\Config;
use Framework\Service\Logger;
use Framework\Service\Router;

class AppKernel
{
    private const DEFAULT_NAMESPACE = 'App\Controller\\';
    private const DEFAULT_CONTROLLER = 'Index';
    private const DEFAULT_ACTION = 'index';

    /**
     * AppKernel constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        DI::create();
        DI::register(Logger::class);
        DI::register(Config::class);
        DI::register(Router::class);

        /** @var Logger $logger */
        $logger = DI::get(Logger::class);
        $logger->info('AppKernel instance created');
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function handle(Request $request)
    {
        /** @var Logger $logger */
        $logger = DI::get(Logger::class);
        $logger->info('AppKernel handle function started');

        $controllerName = $this->getControllerName($request);

        if (!class_exists($controllerName)) {
            throw new FrameworkException('Unable to find class for controlelr ' . $controllerName);
        }

        $controller = new $controllerName($request);
        $actionName = $this->getActionName($request);

        if (!is_callable([$controller, $actionName])) {
            throw new FrameworkException(sprintf(
                'Given action %s for controller %s is not callable',
                $actionName,
                $controllerName
            ));
        }

        call_user_func([$controller, $actionName]);
    }

    /**
     * @param Request $request
     * @return string
     */
    private function getControllerName(Request $request): string
    {
        $controller = self::DEFAULT_CONTROLLER;

        if ($request->getParam('controller')) {
            $controller = $request->getParam('controller');
        }

        return self::DEFAULT_NAMESPACE . $controller . 'Controller';
    }

    /**
     * @param Request $request
     * @return string
     */
    private function getActionName(Request $request): string
    {
        $action = self::DEFAULT_ACTION;

        if ($request->getParam('action')) {
            $action = $request->getParam('action');
        }

        return $action . 'Action';
    }
}
<?php

namespace Framework;

use Framework\Exception\FrameworkException;

class DependencyContainer
{
    /** @var DependencyContainer */
    private static $instance;
    /** @var ServiceInterface[] */
    private $services;

    private function __construct()
    {
    }

    /**
     * @throws FrameworkException
     */
    public static function create(): void
    {
        if (self::$instance) {
            throw new FrameworkException('Unable to create Dependency Container. It is already created');
        }

        self::$instance = new self();
    }

    /**
     * @param string $serviceName
     * @param ServiceInterface $service
     * @return void
     * @throws FrameworkException
     */
    public static function register(string $serviceName, ServiceInterface $service = null): void
    {
        if (null === $service) {
            $service = self::$instance->instantiateService($serviceName);
        }

        if (self::$instance->services && array_key_exists($serviceName, self::$instance->services)) {
            throw new FrameworkException('Unable to register service. It is already registered');
        }

        self::$instance->services[$serviceName] = $service;
    }

    /**
     * @param string $serviceName
     * @return ServiceInterface
     * @throws FrameworkException
     */
    public static function get(string $serviceName): ServiceInterface
    {
        if (!array_key_exists($serviceName, self::$instance->services)) {
            throw new FrameworkException('Unable to find requested service');
        }

        return self::$instance->services[$serviceName];
    }

    /**
     * @param string $serviceName
     * @return ServiceInterface
     * @throws FrameworkException
     */
    private function instantiateService(string $serviceName): ServiceInterface
    {
        if (!class_exists($serviceName)) {
            throw new FrameworkException('Unable to create instance for service ' . $serviceName);
        }

        return new $serviceName;
    }
}
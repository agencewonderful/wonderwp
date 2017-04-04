<?php

namespace WonderWp\Framework\AbstractPlugin;

use WonderWp\Framework\DependencyInjection\Container;
use WonderWp\Framework\Service\ServiceInterface;

interface ManagerInterface
{
    /**
     * Interact with the dependency injection container,
     * to add services, factories, parameters...
     *
     * @param Container $container
     *
     * @return static
     */
    public function register(Container $container);

    /**
     * Run manager
     *
     * @return static
     */
    public function run();

    /**
     * @param string $index
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getConfig($index = null, $default = null);

    /**
     * @param string $key
     * @param mixed  $val : new val for key index
     *
     * @return static
     */
    public function setConfig($key, $val = null);

    /**
     * @return callable[]
     */
    public function getControllers();

    /**
     * @param callable[] $controllers
     *
     * @return static
     */
    public function setControllers(array $controllers);

    /**
     * @param string   $controllerType
     * @param callable $controller
     */
    public function addController($controllerType, $controller);

    /**
     * @param $controllerType
     *
     * @return mixed
     */
    public function getController($controllerType);

    /**
     * @return ServiceInterface[]|Callable[]
     */
    public function getServices();

    /**
     * @param array $services
     *
     * @return static
     */
    public function setServices(array $services);

    /**
     * @param string   $serviceType
     * @param callable $service
     *
     * @return static
     */
    public function addService($serviceType, $service);

    /**
     * @param string $serviceType
     *
     * @return ServiceInterface
     */
    public function getService($serviceType);
}

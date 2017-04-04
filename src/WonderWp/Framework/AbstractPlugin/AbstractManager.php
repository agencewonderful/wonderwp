<?php

namespace WonderWp\Framework\AbstractPlugin;

use WonderWp\Framework\API\ApiServiceInterface;
use WonderWp\Framework\Assets\AssetServiceInterface;
use WonderWp\Framework\DependencyInjection\Container;
use WonderWp\Framework\Hooks\HookServiceInterface;
use WonderWp\Framework\HttpFoundation\Request;
use WonderWp\Framework\Route\RouteServiceInterface;
use WonderWp\Framework\Services\ServiceInterface;
use WonderWp\Framework\Shortcode\ShortcodeServiceInterface;
use WonderWp\Framework\Tasks\TaskServiceInterface;

abstract class AbstractManager implements ManagerInterface
{
    /** @var Container */
    protected $container;
    /** @var Request */
    protected $request;
    /** @var array */
    protected $config = [];
    /** @var callable[] */
    protected $controllers = [];
    /** @var ServiceInterface[]|callable[] */
    protected $services = [];

    const ADMIN_CONTROLLER_TYPE  = 'admin';
    const PUBLIC_CONTROLLER_TYPE = 'public';

    /**
     * @param Container $container
     */
    public function __construct(Container $container = null)
    {
        $this->container = $container ?: Container::getInstance();

        if (method_exists($this, 'autoLoad')) {
            user_error('Calling deprecated autoLoad function on ' . static::class, E_USER_DEPRECATED);
            $autoLoader = $this->container['wwp.autoLoader'];
            $this->autoLoad($autoLoader);
        }

        $this->register($this->container);
    }

    /** @inheritdoc */
    public function getConfig($index = null, $default = null)
    {
        if ($index === null) {
            return $this->config;
        }

        return array_key_exists($index, $this->config) ? $this->config[$index] : $default;
    }

    /** @inheritdoc */
    public function setConfig($key, $val = null)
    {
        $this->config[$key] = $val;

        return $this;
    }

    /** @inheritdoc */
    public function getControllers()
    {
        return $this->controllers;
    }

    /** @inheritdoc */
    public function setControllers(array $controllers)
    {
        $this->controllers = $controllers;

        return $this;
    }

    /** @inheritdoc */
    public function addController($controllerType, $controller)
    {
        $this->controllers[$controllerType] = $controller;
    }

    /** @inheritdoc */
    public function getController($controllerType)
    {
        if (!isset($this->controllers[$controllerType])) {
            return null;
        }

        if (
            !is_object($this->controllers[$controllerType])
            || !method_exists($this->controllers[$controllerType], '__invoke')
        ) {
            return $this->controllers[$controllerType];
        }

        $raw        = $this->controllers[$controllerType];
        $controller = $this->controllers[$controllerType] = $raw($this);

        return $controller;
    }

    /** @inheritdoc */
    public function getServices()
    {
        return $this->services;
    }

    /** @inheritdoc */
    public function setServices(array $services)
    {
        $this->services = $services;

        return $this;
    }

    /** @inheritdoc */
    public function addService($serviceType, $service)
    {
        $this->services[$serviceType] = $service;

        return $this;
    }

    /** @inheritdoc */
    public function getService($serviceType)
    {
        if (!array_key_exists($serviceType, $this->services)) {
            return null;
        }

        if (
            !is_object($this->services[$serviceType])
            || !method_exists($this->services[$serviceType], '__invoke')
        ) {
            return $this->services[$serviceType];
        }

        $raw     = $this->services[$serviceType];
        $service = $this->services[$serviceType] = $raw($this);

        return $service;
    }

    /** @inheritdoc */
    public function register(Container $container)
    {
        // Register Controllers
        // Register Services
        // Register Configs
    }

    /** @inheritdoc */
    public function run()
    {
        $this->request = Request::getInstance();

        /*
         * Call some particular services
         */
        // Hooks
        $hookService = $this->getService(ServiceInterface::HOOK_SERVICE_NAME);
        if ($hookService instanceof HookServiceInterface) {
            $hookService->run();
        }

        // Assets
        $assetService = $this->getService(ServiceInterface::ASSETS_SERVICE_NAME);
        if ($assetService instanceof AssetServiceInterface) {
            $assetManager = $this->container['wwp.assets.manager'];
            $assetManager->addAssetService($assetService);
        }

        // Routes
        $routeService = $this->getService(ServiceInterface::ROUTE_SERVICE_NAME);
        if ($routeService instanceof RouteServiceInterface) {
            $router = $this->container['wwp.routes.router'];
            $router->addService($routeService);
        }

        // Apis
        $apiService = $this->getService(ServiceInterface::API_SERVICE_NAME);
        if ($apiService instanceof ApiServiceInterface) {
            $apiService->registerEndpoints();
        }

        // ShortCode
        $shortCodeService = $this->getService(ServiceInterface::SHORT_CODE_SERVICE_NAME);
        if ($shortCodeService instanceof ShortcodeServiceInterface) {
            $shortCodeService->registerShortcodes();
        }

        // Commands
        $commandService = $this->getService(ServiceInterface::COMMAND_SERVICE_NAME);
        if ($commandService instanceof TaskServiceInterface) {
            $commandService->registerCommands();
        }
    }
}

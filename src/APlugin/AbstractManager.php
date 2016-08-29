<?php

namespace WonderWp\APlugin;

use WonderWp\DI\Container;
use Pimple\Container as PContainer;
use WonderWp\HttpFoundation\Request;
use WonderWp\Route\Router;

abstract class AbstractManager implements ManagerInterface
{

    protected $_container;

    protected $_config;
    protected $_controllers;
    protected $_services;

    public static $ADMINCONTROLLERTYPE = 'admin';
    public static $PUBLICCONTROLLERTYPE = 'public';

    public static $ASSETSSERVICENAME = 'assets';
    public static $HOOKSERVICENAME = 'hooks';
    public static $ROUTESERVICENAME = 'route';
    public static $LISTTABLESERVICENAME = 'listTable';
    public static $MODELFORMSERVICENAME = 'modelForm';
    public static $COMMANDSERVICENAME = 'command';
    public static $VIEWSERVICENAME = 'view';

    public function __construct(Container $container = null)
    {
        $this->_container = $container instanceof Container ? $container : Container::getInstance();

        $autoLoader = $this->_container->offsetGet('wwp.autoLoader');
        $this->autoLoad($autoLoader);

        $this->register($this->_container);
    }

    /**
     * @return mixed
     */
    public function getConfig($index='')
    {
        if(!empty($index)){
            if(isset($this->_config[$index])){
                return $this->_config[$index];
            } else {
                return null;
            }
        }
        return $this->_config;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($key,$val='')
    {
        $this->_config[$key] = $val;
    }

    /**
     * @return mixed
     */
    public function getControllers()
    {
        return $this->_controllers;
    }

    /**
     * @param mixed $controllers
     */
    public function setControllers($controllers)
    {
        $this->_controllers = $controllers;
    }

    public function addController($controllerType,$controller){
        $this->_controllers[$controllerType] = $controller;
    }

    public function getController($controllerType){
        if(!isset($this->_controllers[$controllerType])){ return null; }

        if (
            !is_object($this->_controllers[$controllerType])
            || !method_exists($this->_controllers[$controllerType], '__invoke')
        ) {
            return $this->_controllers[$controllerType];
        }

        $raw = $this->_controllers[$controllerType];
        $controller = $this->_controllers[$controllerType] = $raw($this);
        return $controller;
    }

    /**
     * @return mixed
     */
    public function getServices()
    {
        return $this->_services;
    }

    /**
     * @param mixed $services
     */
    public function setServices($services)
    {
        $this->_services = $services;
    }

    public function addService($serviceType,$service){
        $this->_services[$serviceType] = $service;
    }

    /**
     * @param $serviceType
     */
    public function getService($serviceType){

        if(!isset($this->_services[$serviceType])){ return null; }

        if (
            !is_object($this->_services[$serviceType])
            || !method_exists($this->_services[$serviceType], '__invoke')
        ) {
            return $this->_services[$serviceType];
        }

        $raw = $this->_services[$serviceType];
        $service = $this->_services[$serviceType] = $raw($this);
        return $service;
    }

    public function register(PContainer $container)
    {
        //Register Controllers
        //Register Services
        //Register Configs
    }

    public function run()
    {
        $this->_request = Request::getInstance();

        /*
         * Call some particular services
         */
        //Hooks
        /** @var HookServiceInterface $hookService */
        $hookService = $this->getService(self::$HOOKSERVICENAME);
        if (is_object($hookService)) {
            $hookService->run();
        }

        //Assets
        /** @var AssetServiceInterface $assetService */
        $assetService = $this->getService(self::$ASSETSSERVICENAME);
        if (is_object($assetService)) {
            /** @var AssetManager $assetManager */
            $assetManager = $this->_container->offsetGet('wwp.assets.manager');
            $assetManager->addAssetService($assetService);
        }

        //Routes
        /** @var RouteServiceInterface $routeService */
        $routeService = $this->getService(self::$ROUTESERVICENAME);
        if (is_object($routeService)) {
            /** @var Router $router */
            $router = $this->_container->offsetGet('wwp.Router');
            $router->addService($routeService);
        }

    }

}
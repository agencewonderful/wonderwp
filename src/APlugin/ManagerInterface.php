<?php

namespace WonderWp\APlugin;
use \Composer\Autoload\ClassLoader as AutoLoader;
use Pimple\Container as PContainer;

interface ManagerInterface{
    
    /**
     * Add AutoLoading references to the autoLoader
     * @param AutoLoader $loader
     * @return mixed
     */
    public function autoLoad(AutoLoader $loader);

    /**
     * Interact with the dependency injection container,
     * to add services, factories, parameters...
     * @return mixed
     */
    public function register(PContainer $container);
    
    public function run();

}
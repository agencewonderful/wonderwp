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
     * @param PContainer $container
     * @return $this
     */
    public function register(PContainer $container);

    /**
     * Run manager
     * @return $this
     */
    public function run();

}
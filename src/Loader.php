<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 14/06/2016
 * Time: 17:20
 */
namespace WonderWp;

use WonderWp\AbstractDefinitions\Singleton;
use WonderWp\DI\Container;

class Loader extends Singleton{

    /**
     * Instance
     * @var Loader
     */
    private static $_instance;

    /**
     * Get instance and init if empty
     *
     * Example:
     * <code>
     * $loader = Loader::getInstance();
     * </code>
     *
     * @return Loader
     */
    public static function getInstance () {
        $instance = parent::getInstance();
        $instance->load();
    }

    /**
     * init the framework, loads the config, the autoloader, error handling...
     */
    public function load(){

        $container = Container::getInstance();

        //Define Paths
        $container['path_root'] = ABSPATH . '../../';
        $container['path_framework_root'] = __DIR__;

        //Define Services
        $container['wwp.autoLoader'] = function ($container) {
            return require($container['path_root'].'vendor/autoload.php');
        };

        Container::setInstance($container);

        //Include functions
        require_once(__DIR__.'/functions.php');
    }

}
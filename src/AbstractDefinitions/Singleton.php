<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 15/06/2016
 * Time: 10:53
 */

namespace WonderWp\AbstractDefinitions;

class Singleton{

    private static $_instance = null;

    /**
     * Prevent external instance creation
     */
    private function __construct() { }

    /**
     * Prevent external instance cloning
     */
    private function __clone() { }

    public static function getInstance()
    {
        $className = static::getInstanceClassName();
        if (!self::$_instance instanceof $className) {
            static::setInstance(new $className());
        }
        return self::$_instance;
    }

    public static function setInstance($instance){
        self::$_instance = $instance;
    }

    public static function getInstanceClassName(){
        return static::class;
    }

}

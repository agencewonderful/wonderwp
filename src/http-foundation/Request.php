<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 14/06/2016
 * Time: 14:10
 */

namespace WonderWp\HttpFoundation;

use \Symfony\Component\HttpFoundation\Request as SRequest;

class Request{

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
        if (!self::$_instance instanceof SRequest) {
            self::$_instance = SRequest::createFromGlobals();
        }
        return self::$_instance;
    }

}
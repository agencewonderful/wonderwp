<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 14/06/2016
 * Time: 14:10
 */

namespace WonderWp\HttpFoundation;

use \Symfony\Component\HttpFoundation\Request as SRequest;
use Symfony\Component\HttpFoundation\Session\Session;
use WonderWp\AbstractDefinitions\Singleton;

class Request extends Singleton{

    private static $_instance = null;

    public static function getInstance()
    {
        if (!self::$_instance instanceof SRequest) {
            self::$_instance = SRequest::createFromGlobals();
            self::$_instance->setSession(new Session());
        }
        return self::$_instance;
    }

}

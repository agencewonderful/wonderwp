<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 14/06/2016
 * Time: 17:40
 */

namespace WonderWp\DI;

use Pimple\Container as PContainer;
use WonderWp\AbstractDefinitions\Singleton;

class Container extends Singleton{

    private static $_instance = null;

    /**
     * @return PContainer
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public static function getInstanceClassName(){
        return PContainer::class;
    }

}

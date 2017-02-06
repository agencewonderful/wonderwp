<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 17/08/2016
 * Time: 12:44
 */

namespace WonderWp\Assets;

use WonderWp\DI\Container;

abstract class AbstractAssetEnqueuer implements AssetEnqueuerInterface{

    protected $_container;

    public function __construct()
    {
        $this->_container = Container::getInstance();
    }

}

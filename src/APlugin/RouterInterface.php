<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 17/06/2016
 * Time: 10:24
 */

namespace WonderWp\APlugin;

interface RouterInterface{

    public function addRewrites();

    public function getRoutes();

    public function flushRewrites();

}
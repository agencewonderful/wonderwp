<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 17/06/2016
 * Time: 10:24
 */

namespace WonderWp\Route;

interface RouterInterface{

    public function registerRules();

    public function flushRules();

}

<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 25/08/2016
 * Time: 17:03
 */

namespace WonderWp\Services;

abstract class AbstractService implements ServiceInterface{

    public static $ASSETSSERVICENAME = 'assets';
    public static $HOOKSERVICENAME = 'hooks';
    public static $ROUTESERVICENAME = 'route';
    public static $LISTTABLESERVICENAME = 'listTable';
    public static $MODELFORMSERVICENAME = 'modelForm';
    public static $COMMANDSERVICENAME = 'command';
    public static $VIEWSERVICENAME = 'view';
    public static $APISERVICENAME = 'api';
    public static $SHORTCODESERVICENAME = 'shortcode';

}
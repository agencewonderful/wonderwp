<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 19/09/2016
 * Time: 10:19
 */

namespace WonderWp\Templates\Views;


use WonderWp\DI\Container;

class ListAdminView extends AdminVue
{

    public function registerFrags($prefix, $frags = array())
    {
        $container = Container::getInstance();

        $frags = [
            new VueFrag($container->offsetGet($prefix . '.wwp.path.templates.frags.header')),
            new VueFrag($container->offsetGet($prefix . '.wwp.path.templates.frags.tabs')),
            new VueFrag($container->offsetGet($prefix . '.wwp.path.templates.frags.list')),
            new VueFrag($container->offsetGet($prefix . '.wwp.path.templates.frags.footer'))
        ];
        return parent::registerFrags($prefix, $frags);
    }

}
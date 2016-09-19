<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 19/09/2016
 * Time: 10:19
 */

namespace WonderWp\Templates\Views;


use WonderWp\DI\Container;

class EditAdminView extends AdminVue
{
    public function registerFrags($prefix,$frags = array())
    {
        $container = Container::getInstance();

        $frags = array(
            new VueFrag($container->offsetGet($prefix . '.wwp.path.templates.frags.header')),
            new VueFrag($container->offsetGet($prefix . '.wwp.path.templates.frags.tabs')),
            new VueFrag($container->offsetGet($prefix . '.wwp.path.templates.frags.edit')),
            new VueFrag($container->offsetGet($prefix . '.wwp.path.templates.frags.footer'))
        );

        return parent::registerFrags($prefix, $frags);
    }
}
<?php

namespace WonderWp\Framework\Templates\Views;

use WonderWp\Framework\DependencyInjection\Container;

class EditAdminView extends AdminVue
{
    /** @inheritdoc */
    public function registerFrags($prefix, $frags = [])
    {
        $container = Container::getInstance();

        $frags = [
            new VueFrag($container->offsetGet($prefix . '.wwp.path.templates.frags.header')),
            new VueFrag($container->offsetGet($prefix . '.wwp.path.templates.frags.tabs')),
            new VueFrag($container->offsetGet($prefix . '.wwp.path.templates.frags.edit')),
            new VueFrag($container->offsetGet($prefix . '.wwp.path.templates.frags.footer')),
        ];

        return parent::registerFrags($prefix, $frags);
    }

}

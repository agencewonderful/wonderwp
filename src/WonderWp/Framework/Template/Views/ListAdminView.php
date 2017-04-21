<?php

namespace WonderWp\Framework\Template\Views;

use WonderWp\Framework\DependencyInjection\Container;

class ListAdminView extends AdminVue
{
    /** @inheritdoc */
    public function registerFrags($prefix, array $frags = [])
    {
        $container = Container::getInstance();

        $frags = [
            new VueFrag($container->offsetGet($prefix . '.wwp.path.templates.frags.header')),
            new VueFrag($container->offsetGet($prefix . '.wwp.path.templates.frags.tabs')),
            new VueFrag($container->offsetGet($prefix . '.wwp.path.templates.frags.list')),
            new VueFrag($container->offsetGet($prefix . '.wwp.path.templates.frags.footer')),
        ];

        return parent::registerFrags($prefix, $frags);
    }
}

<?php

namespace WonderWp\Framework\DependencyInjection;

trait ContainerAwareTrait
{
    /** @var \Pimple\Container */
    protected $container;

    public function setContainer(\Pimple\Container $container)
    {
        $this->container = $container;
    }
}

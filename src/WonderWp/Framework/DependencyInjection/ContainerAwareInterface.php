<?php

namespace WonderWp\Framework\DependencyInjection;

interface ContainerAwareInterface
{
    /**
     * @param \Pimple\Container $container
     *
     * @return self
     */
    public function setContainer(\Pimple\Container $container);
}

<?php

namespace WonderWp\Framework\Service;

use WonderWp\Framework\DependencyInjection\Container;
use WonderWp\Framework\DependencyInjection\ContainerAwareInterface;
use WonderWp\Framework\DependencyInjection\ContainerAwareTrait;

abstract class AbstractContainerAwareService extends AbstractService implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Constructor
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->setContainer(Container::getInstance());
    }
}

<?php

namespace WonderWp\Framework\Assets;

use WonderWp\Framework\DependencyInjection\Container;

abstract class AbstractAssetEnqueuer implements AssetEnqueuerInterface
{
    /** @var Container */
    protected $container;

    /** Constructor */
    public function __construct()
    {
        $this->container = Container::getInstance();
    }
}

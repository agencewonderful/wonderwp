<?php

namespace WonderWp\Framework\AbstractPlugin;

use WonderWp\Framework\Service\ServiceInterface;

interface ActivatorInterface extends ServiceInterface
{
    /**
     * Code ran upon plugin activation
     *
     * @return static
     */
    public function activate();
}

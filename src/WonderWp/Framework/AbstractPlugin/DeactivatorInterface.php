<?php

namespace WonderWp\Framework\AbstractPlugin;

use WonderWp\Framework\Service\ServiceInterface;

interface DeactivatorInterface extends ServiceInterface
{
    /**
     * Code ran upon plugin deactivation
     *
     * @return static
     */
    public function deactivate();
}

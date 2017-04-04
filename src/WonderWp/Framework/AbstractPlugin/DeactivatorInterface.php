<?php

namespace WonderWp\Framework\AbstractPlugin;

interface DeactivatorInterface
{
    /**
     * Code ran upon plugin deactivation
     *
     * @return static
     */
    public function deactivate();
}

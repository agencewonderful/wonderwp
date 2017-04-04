<?php

namespace WonderWp\Framework\AbstractPlugin;

interface ActivatorInterface
{
    /**
     * Code ran upon plugin activation
     *
     * @return static
     */
    public function activate();
}

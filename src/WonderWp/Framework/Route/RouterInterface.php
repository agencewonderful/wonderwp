<?php

namespace WonderWp\Framework\Route;

interface RouterInterface
{
    /**
     * Typically where you'll have all your add_rewrite_rule calls
     * @return static
     */
    public function registerRules();

    /**
     * @return static
     */
    public function flushRules();
}

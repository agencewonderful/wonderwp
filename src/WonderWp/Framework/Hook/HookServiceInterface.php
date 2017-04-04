<?php

namespace WonderWp\Framework\Hook;

interface HookServiceInterface
{
    /**
     * Typically where you'll have all your add_action and add_filter calls
     * @return static
     */
    public function run();
}

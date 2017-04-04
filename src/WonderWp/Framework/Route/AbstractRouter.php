<?php

namespace WonderWp\Framework\Route;

abstract class AbstractRouter implements RouterInterface
{

    /**
     * @return $this
     */
    public function flushRules()
    {
        /** @var \WP_Rewrite $wp_rewrite */
        global $wp_rewrite;
        $wp_rewrite->flush_rules();

        return $this;
    }

}

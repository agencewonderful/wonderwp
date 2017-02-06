<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 17/06/2016
 * Time: 10:25
 */

namespace WonderWp\Route;

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

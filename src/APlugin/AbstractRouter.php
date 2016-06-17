<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 17/06/2016
 * Time: 10:25
 */

namespace WonderWp\APlugin;

class AbstractRouter implements RouterInterface{

    public function __construct()
    {
        add_action('generate_rewrite_rules', array($this,'addRewrites'));
        add_action('admin_init', array($this,'flushRewrites'));
    }

    function addRewrites() {
        global $wp_rewrite;
        $wp_rewrite->non_wp_rules = $this->getRoutes() + $wp_rewrite->non_wp_rules;
    }

    public function getRoutes()
    {
        return array();
    }

    public function flushRewrites() {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }



}
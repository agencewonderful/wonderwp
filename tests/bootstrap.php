<?php

define('WP_USE_THEMES', false);

function get_option(){
    return null;
}

include dirname(__DIR__).'/wordpress/wp-includes/load.php';
include dirname(__DIR__).'/wordpress/wp-includes/plugin.php';
include dirname(__DIR__).'/wordpress/wp-includes/l10n.php';
include dirname(__DIR__).'/wordpress/wp-includes/formatting.php';
include dirname(__DIR__).'/wordpress/wp-includes/class-wp-rewrite.php';
include dirname(__DIR__).'/wordpress/wp-includes/rewrite.php';
include dirname(__DIR__).'/wordpress/wp-includes/class-wp.php';

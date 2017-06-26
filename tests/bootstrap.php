<?php

define('WP_USE_THEMES', false);
$root_dir = dirname(__DIR__);

/** @var string Document Root */
$webroot_dir = $root_dir . '/web';
define('CONTENT_DIR', '/app');
define('WP_CONTENT_DIR', $webroot_dir . CONTENT_DIR);
define('WP_LANG_DIR', WP_CONTENT_DIR . '/languages');
define( 'WP_PLUGIN_DIR', __DIR__ . '/my-plugins' );

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

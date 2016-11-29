
## The Main Plugin File

Here's an example of a plugin main file that we'll comment right after:

```
<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://digital.wonderful.fr
 * @since             1.0.0
 * @package           WonderWp
 *
 * @wordpress-plugin
 * Plugin Name:       wwp MyPlugin
 * Plugin URI:        http://digital.wonderful.fr/wonderwp/myplugin
 * Description:       Module d'administration de recettes
 * Version:           1.0.0
 * Author:            WonderfulPlugin
 * Author URI:        http://digital.wonderful.fr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       myplugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//Defining useful constants
define('MYPLUGIN_NAME','myplugin'); //useful in hooks
define('MYPLUGIN_VERSION','1.0.0');
define('MYPLUGIN_TEXTDOMAIN','myplugin');

/**
 * Register activation hook
 * The code that runs during plugin activation.
 */
register_activation_hook( __FILE__, function(){
	require_once plugin_dir_path( __FILE__ ) . 'includes/MyPluginActivator.php';
	$activator = new MyPlugin\MyPluginActivator(MYPLUGIN_VERSION);
	$activator->activate();
} );

/**
 * Register deactivation hook
 * The code that runs during plugin deactivation.
 */
register_deactivation_hook( __FILE__, function(){
	require_once plugin_dir_path( __FILE__ ) . 'includes/MyPluginDeactivator.php';
	$deactivator = new MyPlugin\MyPluginDeactivator();
	$deactivator->deactivate();
} );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 * This class is called the manager
 * Instanciate here because it handles autoloading
 */
require plugin_dir_path( __FILE__ ) . 'includes/MyPluginManager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_my_plugin(){
	$plugin = new MyPlugin\MyPluginManager(MYPLUGIN_NAME,MYPLUGIN_VERSION);
	$plugin->run();
}
run_my_plugin();
```

Ideally, this file should do a few things:

-  Define a few useful constants (plugin name, version and textdomain)
-  Require and instanciate an activator
-  Require and instanciate a deactivator
-  Require and instanciate the plugin manager

The rest of the plugin mechanic is now handled by the manager
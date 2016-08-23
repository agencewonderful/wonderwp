<?php

namespace WonderWp\APlugin;

use WonderWp\DI\Container;
use WonderWp\APlugin\loader;
use Pimple\Container as PContainer;

abstract class AbstractPluginManager extends AbstractManager{

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Wonderwp_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct($plugin_name,$plugin_version) {

        $this->plugin_name = $plugin_name;
        $this->version = $plugin_version;

        parent::__construct();

        $container = Container::getInstance();

        $this->loader = new \WonderWp\APlugin\Loader();

        if($container->offsetExists($this->plugin_name.'.adminController')) {
            $adminController = $container->offsetGet($this->plugin_name . '.adminController');
            $this->define_admin_hooks($adminController);
        }

        if($container->offsetExists($this->plugin_name.'.publicController')) {
            $publicController = $container->offsetGet($this->plugin_name . '.publicController');
            $this->define_public_hooks($publicController);
        }

        $this->_translate();
        add_action( 'wwp_gather_assets', array($this,'registerAssets') );

    }

    public function register(PContainer $container)
    {
        $templatePath = $container->offsetGet('wwp.path.templates.frags');

        $container[$this->plugin_name.'.wwp.listTable.class'] = function(){
            return new \WP_List_Table();
        };
        $container[$this->plugin_name.'.wwp.path.templates.frags.header'] = $templatePath.'/t_header.php';
        $container[$this->plugin_name.'.wwp.path.templates.frags.list'] = $templatePath.'/t_list.php';
        $container[$this->plugin_name.'.wwp.path.templates.frags.edit'] = $templatePath.'/t_edit.php';
        $container[$this->plugin_name.'.wwp.path.templates.frags.tabs'] = $templatePath.'/t_tabs.php';
        $container[$this->plugin_name.'.wwp.path.templates.frags.footer'] = $templatePath.'/t_footer.php';
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    protected function define_admin_hooks($adminController) {
        if(method_exists($adminController, 'customizeMenus')){
            //Admin pages
            $this->loader->add_action( 'admin_menu', $adminController, 'customizeMenus' );
        }
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    protected function define_public_hooks($publicController) {

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        parent::run();
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Wonderwp_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

    protected function _translate(){
        $this->loader->add_action( 'plugins_loaded', $this, 'loadTextdomain' );
    }

    public function loadTextdomain(){}

    public function getAssetService(){
        return $this->_container->offsetExists($this->plugin_name.'.assetService') ? $this->_container->offsetGet($this->plugin_name.'.assetService') : null;
    }

}
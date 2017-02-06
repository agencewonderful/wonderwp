<?php

namespace WonderWp\APlugin;

use Pimple\Container as PContainer;
use WonderWp\Services\AbstractService;

abstract class AbstractPluginManager extends AbstractManager
{

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     *
     * @param string $plugin_name
     * @param string $plugin_version
     */
    public function __construct($plugin_name, $plugin_version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $plugin_version;

        return parent::__construct();
    }

    /**
     * Registers config, controllers, services etc usable by the plugin components
     * @param PContainer $container
     * @return $this
     */
    public function register(PContainer $container)
    {
        //Config
        $prefix = $this->get_plugin_name();
        $this->setConfig('prefix', $prefix);
        $this->setConfig('version', $this->get_version());

        //Services
        $this->addService(AbstractService::$LISTTABLESERVICENAME, function () {
            return new \WP_List_Table();
        });

        //Other
        $templatePath = $container->offsetGet('wwp.path.templates.frags');
        $container[$prefix . '.wwp.path.templates.frags.header'] = $templatePath . '/t_header.php';
        $container[$prefix . '.wwp.path.templates.frags.list'] = $templatePath . '/t_list.php';
        $container[$prefix . '.wwp.path.templates.frags.edit'] = $templatePath . '/t_edit.php';
        $container[$prefix . '.wwp.path.templates.frags.tabs'] = $templatePath . '/t_tabs.php';
        $container[$prefix . '.wwp.path.templates.frags.options'] = $templatePath . '/t_options.php';
        $container[$prefix . '.wwp.path.templates.frags.footer'] = $templatePath . '/t_footer.php';

        $container[$prefix . '.Manager'] = $this;

        return $this;
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

}
<?php

namespace WonderWp\Framework\AbstractPlugin;

use WonderWp\Framework\DependencyInjection\Container;
use WonderWp\Framework\Service\ServiceInterface;

abstract class AbstractPluginManager extends AbstractManager
{
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $pluginName The string used to uniquely identify this plugin.
     */
    protected $pluginName;

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
     * @param string $pluginName
     * @param string $pluginVersion
     */
    public function __construct($pluginName, $pluginVersion)
    {

        $this->pluginName = $pluginName;
        $this->version    = $pluginVersion;

        return parent::__construct();
    }

    /** @inheritdoc */
    public function register(Container $container)
    {
        // Config
        $prefix = $this->getPluginName();
        $this->setConfig('prefix', $prefix);
        $this->setConfig('version', $this->getVersion());

        // Services
        $this->addService(ServiceInterface::LIST_TABLE_SERVICE_NAME, function () {
            return new \WP_List_Table();
        });

        // Other
        $templatePath = $container['wwp.path.templates.frags'];
        foreach (['header', 'list', 'edit', 'tabs', 'options', 'footer'] as $frag) {
            $container["{$prefix}.wwp.path.templates.frags.{$frag}"] = "{$templatePath}/t_{$frag}.php";
        }

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
    public function getPluginName()
    {
        return $this->pluginName;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function getVersion()
    {
        return $this->version;
    }
}

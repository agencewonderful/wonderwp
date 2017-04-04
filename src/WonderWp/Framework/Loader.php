<?php

namespace WonderWp\Framework;

use WonderWp\Framework\Asset\Asset;
use WonderWp\Framework\Asset\AssetManager;
use WonderWp\Framework\Asset\JsonAssetEnqueuer;
use WonderWp\Framework\Asset\JsonAssetExporter;
use WonderWp\Framework\DependencyInjection\Container;
use WonderWp\Framework\DependencyInjection\SingletonInterface;
use WonderWp\Framework\DependencyInjection\SingletonTrait;
use WonderWp\Framework\Form\Form;
use WonderWp\Framework\Form\FormValidator;
use WonderWp\Framework\Form\FormView;
use WonderWp\Framework\Form\FormViewReadOnly;
use WonderWp\Framework\Http\WpRequester;
use WonderWp\Framework\Mail\WpMailer;
use WonderWp\Framework\Panel\Panel;
use WonderWp\Framework\Panel\PanelManager;
use WonderWp\Framework\Route\Router;
use WonderWp\Framework\Template\Views\AdminVue;
use WonderWp\Framework\Template\Views\EditAdminView;
use WonderWp\Framework\Template\Views\ListAdminView;
use WonderWp\Framework\Template\Views\OptionsAdminView;

class Loader implements SingletonInterface
{
    use SingletonTrait {
        SingletonTrait::buildInstance as createInstance;
    }

    /** @inheritdoc */
    public static function buildInstance()
    {
        $instance = static::createInstance();

        $instance->load();

        return $instance;
    }

    /**
     * init the framework, loads the config, the autoloader, error handling...
     */
    public function load()
    {
        // Create DI container
        $container = Container::getInstance();

        /**
         * Define Paths
         */
        $container['path_root']                = ABSPATH . '../../'; // root
        $container['path_framework_root']      = __DIR__; // Framework root
        $container['wwp.path.templates.frags'] = $container['path_framework_root'] . '/Templates/frags'; //Templates

        /**
         * Define Services
         */

        // Autoloader
        /**
         * @param Container $container
         *
         * @return mixed
         */
        $container['wwp.autoLoader'] = function (Container $container) {
            return require($container['path_root'] . 'vendor/autoload.php');
        };

        //Routes
        $container['wwp.routes.router'] = function () {
            return new Router();
        };

        //Assets
        $container['wwp.assets.manager']       = function () {
            return AssetManager::getInstance();
        };
        $container['wwp.assets.exporterClass'] = JsonAssetExporter::class;
        $container['wwp.assets.assetClass']    = Asset::class;
        $container['wwp.assets.manifest.path'] = $container['path_root'] . '/assets.json';
        $container['wwp.assets.enqueuer']      = function ($container) {
            return new JsonAssetEnqueuer($container['wwp.assets.manifest.path']);
        };
        $container['wwp.assets.folder.prefix'] = './';
        $container['wwp.assets.folder.dest']   = '';
        $container['wwp.assets.folder.path']   = str_replace(get_bloginfo('url'), '', str_replace(network_site_url(), '', get_stylesheet_directory_uri()));

        //Forms
        $container['wwp.forms.form']              = $container->factory(function () {
            return new Form();
        });
        $container['wwp.forms.formView']          = $container->factory(function () {
            return new FormView();
        });
        $container['wwp.forms.formView.readOnly'] = $container->factory(function () {
            return new FormViewReadOnly();
        });
        $container['wwp.forms.formValidator']     = $container->factory(function () {
            return new FormValidator();
        });
        $container['wwp.element.edit.success']    = "Element successfully edited";
        $container['wwp.element.add.success']     = "Element successfully added";
        $container['wwp.element.edit.error']      = "Sorry, there's been an error while editing the element";
        $container['wwp.element.add.error']       = "Sorry, there's been an error while adding the element";
        $container['wwp.element.delete.success']  = "Element successfully deleted";
        $container['wwp.element.delete.error']    = "Sorry, there's been an error while deleting the element";

        //Emails
        $container['wwp.emails.mailer'] = $container->factory(function () {
            return new WpMailer();
        });

        //FileSystem
        $container['wwp.fileSystem'] = function () {
            global $wp_filesystem;
            if (empty($wp_filesystem)) {
                require_once(ABSPATH . '/wp-admin/includes/file.php');
                WP_Filesystem();
            }

            return $wp_filesystem;
        };

        //Views
        $container['wwp.views.baseAdmin']    = function () {
            return new AdminVue();
        };
        $container['wwp.views.listAdmin']    = function () {
            return new ListAdminView();
        };
        $container['wwp.views.editAdmin']    = function () {
            return new EditAdminView();
        };
        $container['wwp.views.optionsAdmin'] = function () {
            return new OptionsAdminView();
        };

        //Panels
        $container['wwp.panel.Manager'] = function () {
            return new PanelManager();
        };
        $container['wwp.panel.Panel']   = $container->factory(function () {
            return new Panel();
        });

        $container['wwp.http.requester'] = function () {
            return new WpRequester();
        };

        /**
         * Make container available
         */
        Container::setInstance($container);
    }
}

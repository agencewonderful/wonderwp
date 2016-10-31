<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 14/06/2016
 * Time: 17:20
 */
namespace WonderWp;

use Doctrine\DBAL\Logging\DebugStack;
use Doctrine\DBAL\Logging\EchoSQLLogger;
use WonderWp\AbstractDefinitions\Singleton;
use WonderWp\Assets\Asset;
use WonderWp\Assets\AssetEnqueuer;
use WonderWp\Assets\AssetManager;
use WonderWp\Assets\AssetExporter;
use WonderWp\Assets\AssetRenderer;
use WonderWp\Assets\DirectAssetEnqueuer;
use WonderWp\Assets\JsonAssetEnqueuer;
use WonderWp\Assets\JsonAssetExporter;
use WonderWp\DI\Container;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use WonderWp\Forms\Form;
use WonderWp\Forms\FormValidator;
use WonderWp\Forms\ModelForm;
use WonderWp\Forms\FormView;
use WonderWp\Mail\WwpWpMailer;
use WonderWp\Panel\Panel;
use WonderWp\Panel\PanelManager;
use WonderWp\Route\Router;
use WonderWp\Templates\Views\AdminVue;
use WonderWp\Templates\Views\EditAdminView;
use WonderWp\Templates\Views\ListAdminView;
use WonderWp\Templates\Views\OptionsAdminView;

class Loader extends Singleton{

    /**
     * Instance
     * @var Loader
     */
    private static $_instance;

    /**
     * Get instance and init if empty
     *
     * Example:
     * <code>
     * $loader = Loader::getInstance();
     * </code>
     *
     * @return Loader
     */
    public static function getInstance () {
        $instance = parent::getInstance();
        $instance->load();
    }

    /**
     * init the framework, loads the config, the autoloader, error handling...
     */
    public function load(){

        //Create DI container
        $container = Container::getInstance();

        /**
         * Define Paths
         */
        $container['path_root'] = ABSPATH . '../../'; //root
        $container['path_framework_root'] = __DIR__; //Framework root
        $container['wwp.path.templates.frags'] = $container['path_framework_root'].'/Templates/frags'; //Templates

        /**
         * Define Services
         */

        //Autoloader
        $container['wwp.autoLoader'] = function ($container) {
            return require($container['path_root'].'vendor/autoload.php');
        };

        $container['doctrine.sqlLogger'] = function($container){
            $logger = new DebugStack();
            $loggerDumper = function() use($logger){
                echo'<div id="doctrineQueryLog">';
                dump($logger,5);
                echo'</div>';
            };
            if(defined('SAVEQUERIES') && SAVEQUERIES) {
                add_action('wp_footer', $loggerDumper);
                add_action('admin_footer', $loggerDumper);
            }
            return $logger;
        };

        //Entity Manager
        $container['entityManager'] = function($container) {
            global $wpdb;

            //Paths
            $autoLoader = $container->offsetGet('wwp.autoLoader');
            $multiPaths = $autoLoader->getPrefixesPsr4();

            $paths = array();
            if(!empty($multiPaths)){ foreach($multiPaths as $pathName=>$aPath){
                if(strpos($pathName,'WonderWp')!==false) {
                    $paths = array_merge($paths, $aPath);
                }
            }}

            //Env
            $isDevMode = WP_ENV=='development';
            $proxyDir=null;
            $cache=null;
            $useSimpleAnnotationReader = false; //Keep it false to use @ORM annotations

            //Create doctrine config
            $config = Setup::createConfiguration($isDevMode, $proxyDir, $cache);

                //Setup annotation driver
                $anDriver = $config->newDefaultAnnotationDriver($paths, $useSimpleAnnotationReader);
                $anDriver->addExcludePaths([
                    $container['path_framework_root'].'/Templates/frags'
                ]);

            $config->setMetadataDriverImpl($anDriver);
            $config->addCustomNumericFunction('RAND', 'WonderWp\DB\Rand');
            $sqlLogger = $container->offsetGet('doctrine.sqlLogger');
            $config->setSQLLogger($sqlLogger);

            //Evm, used to add wordpress table prefix
            $evm = new \Doctrine\Common\EventManager;

            //Prefix
            $tablePrefix = new \WonderWp\DB\TablePrefix($wpdb->prefix);
            $evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);

            //Connection configuration
            $dbParams = array(
                'driver'   => 'pdo_mysql',
                'user'     => DB_USER,
                'password' => DB_PASSWORD,
                'dbname'   => DB_NAME,
                'host'     => DB_HOST
            );

            $em = EntityManager::create($dbParams, $config, $evm);

            return $em;
        };

        //Routes
        $container['wwp.routes.router'] = function(){
            return new Router();
        };

        //Assets
        $container['wwp.assets.manager'] = function(){
            return AssetManager::getInstance();
        };
        $container['wwp.assets.exporterClass'] = JsonAssetExporter::class;
        $container['wwp.assets.assetClass'] = Asset::class;
        $container['wwp.assets.manifest.path'] = $container['path_root'].'/assets.json';
        $container['wwp.assets.enqueuer'] = function($container){
            return new JsonAssetEnqueuer($container['wwp.assets.manifest.path']);
        };
        $container['wwp.assets.folder.prefix'] = './';
        $container['wwp.assets.folder.path'] = str_replace(get_bloginfo('url'),'',get_stylesheet_directory_uri());

        //Forms
        $container['wwp.forms.modelForm'] = $container->factory(function($c){
            return new ModelForm();
        });
        $container['wwp.forms.form'] = $container->factory(function($c){
            return new Form();
        });
        $container['wwp.forms.formView'] = $container->factory(function($c){
            return new FormView();
        });
        $container['wwp.forms.formValidator'] = $container->factory(function($c){
            return new FormValidator();
        });
        $container['wwp.element.edit.success'] = "Element successfully edited";
        $container['wwp.element.add.success'] = "Element successfully added";
        $container['wwp.element.edit.error'] = "Sorry, there's been an error while editing the element";
        $container['wwp.element.add.error'] = "Sorry, there's been an error while adding the element";
        $container['wwp.element.delete.success'] = "Element successfully deleted";
        $container['wwp.element.delete.error'] = "Sorry, there's been an error while deleting the element";

        //Emails
        $container['wwp.emails.mailer'] = $container->factory(function($c){
            return new WwpWpMailer();
        });

        //FileSystem
        $container['wwp.fileSystem'] = function(){
            global $wp_filesystem;
            if (empty($wp_filesystem)) {
                require_once (ABSPATH . '/wp-admin/includes/file.php');
                WP_Filesystem();
            }
            return $wp_filesystem;
        };

        //Views
        $container['wwp.views.baseAdmin'] = function() {
            return new AdminVue();
        };
        $container['wwp.views.listAdmin'] = function() {
            return new ListAdminView();
        };
        $container['wwp.views.editAdmin'] = function() {
            return new EditAdminView();
        };
        $container['wwp.views.optionsAdmin'] = function() {
            return new OptionsAdminView();
        };

        //Panels
        $container['wwp.panel.Manager'] = function(){
            return new PanelManager();
        };
        $container['wwp.panel.Panel'] = $container->factory(function($c){
            return new Panel();
        });

        /**
         * Make container available
         */
        Container::setInstance($container);

        //Include functions
        require_once(__DIR__.'/functions.php');
    }

}
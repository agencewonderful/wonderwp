<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 14/06/2016
 * Time: 17:20
 */
namespace WonderWp;

use WonderWp\AbstractDefinitions\Singleton;
use WonderWp\Assets\Asset;
use WonderWp\DI\Container;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

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

        //Entity Manager
        $container['entityManager'] = function($container) {
            global $wpdb;

            //Paths
            $autoLoader = $container->offsetGet('wwp.autoLoader');
            $multiPaths = $autoLoader->getPrefixesPsr4();
            $paths = array();
            if(!empty($multiPaths)){ foreach($multiPaths as $aPath){
                $paths = array_merge($paths,$aPath);
            }}
            //Env
            $isDevMode = WP_ENV=='development';
            //Evm
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
            $conn = \Doctrine\DBAL\DriverManager::getConnection($dbParams,null,$evm);

            $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
            return EntityManager::create($conn, $config, $evm);
        };

        //AssetsManager
        $container['wwp.assets.manager'] = function(){
            return AssetsManager::getInstance();
        };
        $container['wwp.assets.assetClass'] = Asset::class;

        /**
         * Make container available
         */
        Container::setInstance($container);

        //Include functions
        require_once(__DIR__.'/functions.php');
    }

}
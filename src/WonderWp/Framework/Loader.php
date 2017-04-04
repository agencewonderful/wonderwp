<?php

namespace WonderWp\Framework;

use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Gedmo\DoctrineExtensions;
use Gedmo\IpTraceable\IpTraceableListener;
use Gedmo\Mapping\MappedEventSubscriber;
use Gedmo\ReferenceIntegrity\ReferenceIntegrityListener;
use Gedmo\References\ReferencesListener;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Gedmo\Sortable\SortableListener;
use Gedmo\Timestampable\TimestampableListener;
use Gedmo\Tree\TreeListener;
use Gedmo\Uploadable\UploadableListener;
use Sluggable\Fixture\Issue939\SluggableListener;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use WonderWp\Framework\Assets\Asset;
use WonderWp\Framework\Assets\AssetManager;
use WonderWp\Framework\Assets\JsonAssetEnqueuer;
use WonderWp\Framework\Assets\JsonAssetExporter;
use WonderWp\Framework\DB\DebugStack;
use WonderWp\Framework\DB\TablePrefix;
use WonderWp\Framework\DependencyInjection\Container;
use WonderWp\Framework\Form\Form;
use WonderWp\Framework\Form\FormValidator;
use WonderWp\Framework\Form\FormView;
use WonderWp\Framework\Form\FormViewReadOnly;
use WonderWp\Framework\Form\ModelForm;
use WonderWp\Framework\Http\WpRequester;
use WonderWp\Framework\Mail\WpMailer;
use WonderWp\Framework\Panel\Panel;
use WonderWp\Framework\Panel\PanelManager;
use WonderWp\Framework\Route\Router;
use WonderWp\Framework\Templates\Views\AdminVue;
use WonderWp\Framework\Templates\Views\EditAdminView;
use WonderWp\Framework\Templates\Views\ListAdminView;
use WonderWp\Framework\Templates\Views\OptionsAdminView;

class Loader implements SingletonInterface
{
    use SingletonTrait {
        SingletonTrait::buildInstance as createInstance;
    }

    /**
     * @var bool
     */
    private $gedmoLoaded = false;

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
        die('load');
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

        $container['doctrine.sqlLogger'] = function () {
            $logger       = new DebugStack();
            $loggerDumper = function () use ($logger) {
                echo '<div id="doctrineQueryLog">';
                dump($logger, 5);
                echo '</div>';
            };
            if (defined('SAVEQUERIES') && SAVEQUERIES) {
                add_action('wp_footer', $loggerDumper);
                add_action('admin_footer', $loggerDumper);
            }

            return $logger;
        };

        //Entity Manager
        /**
         * @param PContainer $container
         *
         * @return EntityManager
         */
        $container['entityManager'] = function (Container $container) {
            global $wpdb;

            //Paths
            $autoLoader = $container['wwp.autoLoader'];
            $multiPaths = $autoLoader->getPrefixesPsr4();

            $paths = [];
            if (!empty($multiPaths)) {
                foreach ($multiPaths as $pathName => $aPath) {
                    if (strpos($pathName, 'WonderWp') !== false) {
                        $paths = array_merge($paths, $aPath);
                    }
                }
            }

            //Env
            $isDevMode                 = WP_ENV == 'development';
            $proxyDir                  = null;
            $cache                     = null;
            $useSimpleAnnotationReader = false; //Keep it false to use @ORM annotations

            //Create doctrine config
            $config = Setup::createConfiguration($isDevMode, $proxyDir, $cache);

            //Setup annotation driver
            $anDriver = $config->newDefaultAnnotationDriver($paths, $useSimpleAnnotationReader);
            $anDriver->addExcludePaths([
                $container['path_framework_root'] . '/Templates/frags',
            ]);

            $config->setMetadataDriverImpl($anDriver);
            $config->addCustomNumericFunction('RAND', 'WonderWp\DB\Rand');
            $sqlLogger = $container['doctrine.sqlLogger'];
            $config->setSQLLogger($sqlLogger);
            $config->setNamingStrategy(new UnderscoreNamingStrategy());
            $uploadsDir = wp_get_upload_dir();
            $config->setProxyDir($uploadsDir['basedir'] . DIRECTORY_SEPARATOR . 'doctrine');

            //Evm, used to add wordpress table prefix
            $evm = new EventManager();

            //Prefix
            $tablePrefix = new TablePrefix($wpdb->prefix);
            $evm->addEventListener(Events::loadClassMetadata, $tablePrefix);

            if (defined('USE_GEDMO_SLUGGABLE') && USE_GEDMO_SLUGGABLE === true) {
                // Gedmo Sluggable
                $this->loadGedmoExtension(new SluggableListener(), $anDriver, $evm);
            }

            if (defined('USE_GEDMO_TREE') && USE_GEDMO_TREE === true) {
                // Gedmo Tree
                $this->loadGedmoExtension(new TreeListener(), $anDriver, $evm);
            }

            if (defined('USE_GEDMO_TIMESTAMPABLE') && USE_GEDMO_TIMESTAMPABLE === true) {
                // Gedmo Timestampable
                $this->loadGedmoExtension(new TimestampableListener(), $anDriver, $evm);
            }

            if (defined('USE_GEDMO_SORTABLE') && USE_GEDMO_SORTABLE === true) {
                // Gedmo Sortable
                $this->loadGedmoExtension(new SortableListener(), $anDriver, $evm);
            }

            if (defined('USE_GEDMO_SOFT_DELETEABLE') && USE_GEDMO_SOFT_DELETEABLE === true) {
                // Gedmo SoftDeleteable
                $this->loadGedmoExtension(new SoftDeleteableListener(), $anDriver, $evm);
            }

            if (defined('USE_GEDMO_UPLOADABLE') && USE_GEDMO_UPLOADABLE === true) {
                // Gedmo Uploadable
                $listener = new UploadableListener();

                if (defined('GEDMO_UPLOADABLE_DIRECTORY')) {
                    $listener->setDefaultPath(GEDMO_UPLOADABLE_DIRECTORY);
                }

                $this->loadGedmoExtension($listener, $anDriver, $evm);
            }

            if (defined('USE_GEDMO_REFERENCES') && USE_GEDMO_REFERENCES === true) {
                // Gedmo References
                $this->loadGedmoExtension(new ReferencesListener(), $anDriver, $evm);
            }

            if (defined('USE_GEDMO_REFERENCE_INTEGRITY') && USE_GEDMO_REFERENCE_INTEGRITY === true) {
                // Gedmo Reference Integrity
                $this->loadGedmoExtension(new ReferenceIntegrityListener(), $anDriver, $evm);
            }

            if (defined('USE_GEDMO_IP_TRACEABLE') && USE_GEDMO_IP_TRACEABLE === true) {
                // Gedmo IpTraceable
                $this->loadGedmoExtension(new IpTraceableListener(), $anDriver, $evm);
            }

            /* TO DO Get $username value
            if (defined('USE_GEDMO_LOGGABLE') && USE_GEDMO_LOGGABLE === true) {
                // Gedmo Loggable
                $listener = new \Gedmo\Loggable\LoggableListener();
                $listener->setUsername($username);
                $this->loadGedmoExtension($listener, $anDriver, $evm);
            }
            */

            /* TO DO Get $defaultLocale value
            if (defined('USE_GEDMO_TRANSLATABLE') && USE_GEDMO_TRANSLATABLE === true) {
                // Gedmo Translatable
                $listener = new \Gedmo\Translatable\TranslatableListener();
                $listener->setTranslatableLocale($defaultLocale);
                $listener->setDefaultLocale($defaultLocale);
                $this->loadGedmoExtension($listener, $anDriver, $evm);
            }
            */

            /* TO DO Get $connectedUser value
            if (defined('USE_GEDMO_BLAMEABLE') && USE_GEDMO_BLAMEABLE === true) {
                // Gedmo Blameable
                $listener = new \Gedmo\Blameable\BlameableListener();
                $listener->setUserValue($connectedUser);
                $this->loadGedmoExtension($listener, $anDriver, $evm);
            }
            */

            //Connection configuration
            $dbParams = [
                'driver'        => 'pdo_mysql',
                'user'          => DB_USER,
                'password'      => DB_PASSWORD,
                'dbname'        => DB_NAME,
                'host'          => DB_HOST,
                'charset'       => 'utf8',
                'driverOptions' => [
                    1002 => 'SET NAMES utf8',
                ],
            ];

            $em = EntityManager::create($dbParams, $config, $evm);

            return $em;
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
        $container['wwp.forms.modelForm']         = $container->factory(function () {
            return new ModelForm();
        });
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

    /**
     * @param MappedEventSubscriber $listener
     * @param AnnotationDriver      $annotationDriver
     * @param EventManager          $eventManager
     *
     * @return void
     */
    protected function loadGedmoExtension(MappedEventSubscriber $listener, AnnotationDriver $annotationDriver, EventManager $eventManager)
    {
        if (!$this->gedmoLoaded) {
            DoctrineExtensions::registerAbstractMappingIntoDriverChainORM(new MappingDriverChain(), $annotationDriver->getReader());
        }

        $listener->setAnnotationReader($annotationDriver->getReader());
        $eventManager->addEventSubscriber($listener);
    }
}

<?php

namespace WonderWp\APlugin;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use WonderWp\DI\Container;

abstract class AbstractPluginActivator implements ActivatorInterface{

    protected $_version;

    public function __construct($version)
    {
        $this->_version = $version;
    }

    protected function _createTable($entityName)
    {
        global $wpdb;
        
        $installed_ver = get_option( $entityName."_table_version" );

        if ( $installed_ver != $this->_version ) {

            $container = Container::getInstance();
            /** @var EntityManager $entityManager */
            $entityManager = $container->offsetGet('entityManager');
            $st = new SchemaTool($entityManager);

            $metas = $entityManager->getClassMetaData($entityName);
            $table_sql = $st->getCreateSchemaSql(array($metas));

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $table_sql );

            update_option( $entityName."_table_version", $this->_version );
        }
    }    

}
<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 20/06/2016
 * Time: 16:37
 */

namespace WonderWp\APlugin;

use WonderWp\DI\Container;
use WonderWp\HttpFoundation\Request;
use WonderWp\Plugin\Actu\Actu;
use WonderWp\Templates\VueFrag;

abstract class AbstractPluginBackendController{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    protected $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    protected $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    public function customizeMenus(){

    }

    public function route(){
        $request = Request::getInstance();
        $action = $request->get('action','list').'Action';

        if(method_exists($this,$action)) {
            call_user_func(array($this, $action));
        } else {
            echo "Method $action not callable on this controller";
        }
    }

    public function listAction(){
        $container = Container::getInstance();

        $listTableInstance = $container->offsetGet($this->plugin_name.'.wwp.listTable.class');

        $vue = $container->offsetGet('wwp.basePlugin.backendView');
        $vue->addFrag(new VueFrag( $container->offsetGet($this->plugin_name.'.wwp.path.templates.frags.header'),array('title'=>get_admin_page_title())));
        $vue->addFrag(new VueFrag( $container->offsetGet($this->plugin_name.'.wwp.path.templates.frags.list'),array('listTableInstance'=>$listTableInstance)));
        $vue->addFrag(new VueFrag( $container->offsetGet($this->plugin_name.'.wwp.path.templates.frags.footer')));
        $vue->render();
    }

    public function editAction(){
        $container = Container::getInstance();
        $em = $container->offsetGet('entityManager');
        $request = Request::getInstance();
        $id = $request->get('id',0);

        $entityName = $container->offsetGet($this->plugin_name.'.wwp.entityName');

        if(!empty($id)) {
            $item = $em->find($entityName, $id);
            \WonderWp\trace($item);
        }
    }

}
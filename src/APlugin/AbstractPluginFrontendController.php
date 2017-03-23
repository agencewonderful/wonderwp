<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 02/09/2016
 * Time: 10:05
 */

namespace WonderWp\APlugin;

use Doctrine\ORM\EntityManager;
use WonderWp\DI\Container;

abstract class AbstractPluginFrontendController{

    protected $_container;
    /**
     * EntityManager
     * @var EntityManager
     */
    protected $_entityManager;
    /**
     * Plugin Manager
     * @var AbstractPluginManager
     */
    protected $_manager;

    /**
     * @return ManagerInterface
     */
    public function getManager()
    {
        return $this->_manager;
    }

    /**
     * @param ManagerInterface $manager
     */
    public function setManager($manager)
    {
        $this->_manager = $manager;
    }

    public function __construct(ManagerInterface $manager)
    {
        $this->_manager = $manager;
        $this->_container = Container::getInstance();
        $this->_entityManager = $this->_container->offsetGet('entityManager');
    }

    public function handleShortcode($atts){
        if(!empty($atts['action']) && method_exists($this,$atts['action'].'Action')){
            return call_user_func_array(array($this,$atts['action'].'Action'),$atts);
        } else {
            return $this->defaultAction($atts);
        }
    }

    public function defaultAction($atts){

    }

    public function renderView($viewName,$params){
        $viewContent = '';
        $pluginRoot = $this->_manager->getConfig('path.root');
        if(!empty($pluginRoot)){

            $viewDest = \WonderWp\get_plugin_file($pluginRoot,DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.$viewName.'.php');
            if(!file_exists($viewDest)){
                $viewDest = $pluginRoot.'/public/views/'.$viewName.'.php';
            }
            if(file_exists($viewDest)){
                ob_start();
                //Spread attributes
                if(!empty($params)){ foreach($params as $key=>$val){
                    $$key = $val;
                }}
                include $viewDest;
                $viewContent = ob_get_contents();
                ob_end_clean();
            }
        }
        return $viewContent;
    }

}

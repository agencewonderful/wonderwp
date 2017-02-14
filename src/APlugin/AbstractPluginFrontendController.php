<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 02/09/2016
 * Time: 10:05
 */

namespace WonderWp\APlugin;

use WonderWp\DI\Container;

abstract class AbstractPluginFrontendController{

    protected $_container;
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
        return $this->defaultAction($atts);
    }

    public function defaultAction($atts){

    }

    public function renderView($viewName,$params){
        $viewContent = '';
        $pluginRoot = $this->_manager->getConfig('path.root');
        if(!empty($pluginRoot)){
            $frags = explode(DIRECTORY_SEPARATOR,trim($pluginRoot,DIRECTORY_SEPARATOR));
            $pluginFolder = end($frags);
            $viewDest = get_stylesheet_directory().DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$pluginFolder.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.$viewName.'.php';
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

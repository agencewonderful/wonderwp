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
use WonderWp\Services\AbstractService;
use WonderWp\Templates\VueFrag;

abstract class AbstractPluginBackendController{


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

    /**
     * AbstractPluginBackendController constructor.
     * @param ManagerInterface $manager
     */
    public function __construct( ManagerInterface $manager ) {

        $this->_manager = $manager;

    }

    public function getRoute(){
        $request = Request::getInstance();
        $action = $request->get('action','');
        if(empty($action)){
            $tabIndex = $request->get('tab',1);
            $tabs = $this->getTabs();
            if(!empty($tabs[$tabIndex]) && !empty($tabs[$tabIndex]['action'])){
                $action = $tabs[$tabIndex]['action'];
            }
            if(empty($action)){ $action = 'list'; }
        }
        return $action;
    }

    public function route(){
        $action = $this->getRoute();
        $this->execRoute($action);
    }

    public function execRoute($action){
        $action.='Action';

        if(method_exists($this,$action)) {
            call_user_func(array($this, $action));
        } else {
            echo "Method $action not callable on this controller";
        }
    }

    public function listAction(ListTable $listTableInstance=null){
        $container = Container::getInstance();

        if(empty($listTableInstance)) {
            $listTableInstance = $this->_manager->getService(AbstractService::$LISTTABLESERVICENAME);
        }

        $entityName = $listTableInstance->getEntityName();
        if(empty($entityName)){ $entityName = $this->_manager->getConfig('entityName'); }
        if(!empty($entityName)){ $listTableInstance->setEntityName($entityName); }

        $textDomain = $listTableInstance->getTextDomain();
        if(empty($textDomain)){ $textDomain = $this->_manager->getConfig('textDomain'); }
        if(!empty($textDomain)){ $listTableInstance->setTextDomain($textDomain); }

        $tabs = $this->getTabs();

        $prefix = $this->_manager->getConfig('prefix');
        $vue = $container->offsetGet('wwp.basePlugin.backendView');
        $vue->addFrag(new VueFrag( $container->offsetGet($prefix.'.wwp.path.templates.frags.header'),array('title'=>get_admin_page_title())));
        if(!empty($tabs)){ $vue->addFrag(new VueFrag( $container->offsetGet($prefix.'.wwp.path.templates.frags.tabs'),array('tabs'=>$tabs))); }
        $vue->addFrag(new VueFrag( $container->offsetGet($prefix.'.wwp.path.templates.frags.list'),array('listTableInstance'=>$listTableInstance)));
        $vue->addFrag(new VueFrag( $container->offsetGet($prefix.'.wwp.path.templates.frags.footer')));
        $vue->render();
    }

    public function editAction(){
        $container = Container::getInstance();
        $em = $container->offsetGet('entityManager');
        $request = Request::getInstance();
        $prefix = $this->_manager->getConfig('prefix');

        //Load entity
        $id = $request->get('id',0);
        $entityName = $this->_manager->getConfig('entityName');
        if(!empty($id)) {
            $item = $em->find($entityName, $id);
        } else {
            $item = new $entityName();
        }

        //Get new form instance
        /* @var $formInstance \WonderWp\Forms\FormInterface */
        $formInstance = $container->offsetGet('wwp.forms.form');

        //Build model form, by adding fields corresponding to the model attributes, to the form instance
        /* @var $modelForm \WonderWp\Forms\ModelForm */
        $modelForm = $this->_manager->getService(AbstractService::$MODELFORMSERVICENAME);
        if(!is_object($modelForm)){ $modelForm = $container->offsetGet('wwp.forms.modelForm'); }

        $textDomain = $modelForm->getTextDomain();
        if(empty($textDomain)){ $textDomain = $this->_manager->getConfig('textDomain'); }
        if(!empty($textDomain)){ $modelForm->setTextDomain($textDomain); }

        $modelForm->setModelInstance($item);
        $modelForm->setFormInstance($formInstance)->buildForm();

        $errors = array();
        if ($request->getMethod() == 'POST') {
            $formValidator = $container->offsetExists($prefix.'wwp.forms.formValidator') ? $container->offsetGet($prefix.'wwp.forms.formValidator') : $container->offsetGet('wwp.forms.formValidator');
            $errors = $modelForm->handleRequest($request,$formValidator);
        }

        $formInstance = $modelForm->getFormInstance();

        //Form View
        /* @var $formView \WonderWp\Forms\FormViewInterface */
        $formView = $container->offsetGet('wwp.forms.formView');
        $formView->setFormInstance($formInstance);

        $tabs = $this->getTabs();

        $vue = $container->offsetGet('wwp.basePlugin.backendView');
        $vue->addFrag(new VueFrag( $container->offsetGet($prefix.'.wwp.path.templates.frags.header'),array('title'=>get_admin_page_title())));
        if(!empty($tabs)){ $vue->addFrag(new VueFrag( $container->offsetGet($prefix.'.wwp.path.templates.frags.tabs'),array('tabs'=>$tabs))); }
        $vue->addFrag(new VueFrag( $container->offsetGet($prefix.'.wwp.path.templates.frags.edit'),array('formView'=>$formView, 'formSubmitted'=>($request->getMethod() == 'POST'), 'formValid'=>(empty($errors)))));
        $vue->addFrag(new VueFrag( $container->offsetGet($prefix.'.wwp.path.templates.frags.footer')));
        $vue->render();

    }

    public function deleteAction(){
        $container = Container::getInstance();
        $em = $container->offsetGet('entityManager');
        $request = Request::getInstance();

        //Load entity
        $id = $request->get('id',0);
        $entityName = $container->offsetGet($this->plugin_name.'.wwp.entityName');
        if(!empty($id)) {
            $item = $em->find($entityName, $id);
            $em->remove($item);
            $em->flush();
        }
        $request->query->remove('action');
        $request->query->remove('id');

        \WonderWp\redirect($request->getBaseUrl().'?'.http_build_query($request->query->all()));
    }

    public function getTabs(){

    }

    public function getMinCapability(){
        return 'read';
    }
}
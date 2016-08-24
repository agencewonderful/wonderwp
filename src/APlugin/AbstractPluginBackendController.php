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
            $listTableInstance = $container->offsetGet($this->plugin_name . '.wwp.listTable.class');
        }

        $entityName = $listTableInstance->getEntityName();
        if(empty($entityName) && $container->offsetExists($this->plugin_name . '.wwp.entityName')){
            $listTableInstance->setEntityName($container->offsetGet($this->plugin_name . '.wwp.entityName'));
        }

        $textDomain = $listTableInstance->getTextDomain();
        if(empty($textDomain) && $container->offsetExists($this->plugin_name . '.wwp.textDomain')){
            $listTableInstance->setTextDomain($container->offsetGet($this->plugin_name . '.wwp.textDomain'));
        }

        $tabs = $this->getTabs();

        $vue = $container->offsetGet('wwp.basePlugin.backendView');
        $vue->addFrag(new VueFrag( $container->offsetGet($this->plugin_name.'.wwp.path.templates.frags.header'),array('title'=>get_admin_page_title())));
        if(!empty($tabs)){ $vue->addFrag(new VueFrag( $container->offsetGet($this->plugin_name.'.wwp.path.templates.frags.tabs'),array('tabs'=>$tabs))); }
        $vue->addFrag(new VueFrag( $container->offsetGet($this->plugin_name.'.wwp.path.templates.frags.list'),array('listTableInstance'=>$listTableInstance)));
        $vue->addFrag(new VueFrag( $container->offsetGet($this->plugin_name.'.wwp.path.templates.frags.footer')));
        $vue->render();
    }

    public function editAction(){
        $container = Container::getInstance();
        $em = $container->offsetGet('entityManager');
        $request = Request::getInstance();

        //Load entity
        $id = $request->get('id',0);
        $entityName = $container->offsetGet($this->plugin_name.'.wwp.entityName');
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
        $modelForm = $container->offsetExists($this->plugin_name.'wwp.forms.modelForm') ? $container->offsetGet($this->plugin_name.'wwp.forms.modelForm') : $container->offsetGet('wwp.forms.modelForm');
        $textDomain = $modelForm->getTextDomain();
        if(empty($textDomain) && $container->offsetExists($this->plugin_name . '.wwp.textDomain')){
            $container->offsetGet($this->plugin_name . '.wwp.textDomain');
            $modelForm->setTextDomain($container->offsetGet($this->plugin_name . '.wwp.textDomain'));
        }
        $modelForm->setModelInstance($item);
        $modelForm->setFormInstance($formInstance)->buildForm();

        $errors = array();
        if ($request->getMethod() == 'POST') {
            $formValidator = $container->offsetExists($this->plugin_name.'wwp.forms.formValidator') ? $container->offsetGet($this->plugin_name.'wwp.forms.formValidator') : $container->offsetGet('wwp.forms.formValidator');
            $errors = $modelForm->handleRequest($request,$formValidator);
        }

        $formInstance = $modelForm->getFormInstance();

        //Form View
        /* @var $formView \WonderWp\Forms\FormViewInterface */
        $formView = $container->offsetGet('wwp.forms.formView');
        $formView->setFormInstance($formInstance);

        $tabs = $this->getTabs();

        $vue = $container->offsetGet('wwp.basePlugin.backendView');
        $vue->addFrag(new VueFrag( $container->offsetGet($this->plugin_name.'.wwp.path.templates.frags.header'),array('title'=>get_admin_page_title())));
        if(!empty($tabs)){ $vue->addFrag(new VueFrag( $container->offsetGet($this->plugin_name.'.wwp.path.templates.frags.tabs'),array('tabs'=>$tabs))); }
        $vue->addFrag(new VueFrag( $container->offsetGet($this->plugin_name.'.wwp.path.templates.frags.edit'),array('formView'=>$formView, 'formSubmitted'=>($request->getMethod() == 'POST'), 'formValid'=>(empty($errors)))));
        $vue->addFrag(new VueFrag( $container->offsetGet($this->plugin_name.'.wwp.path.templates.frags.footer')));
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
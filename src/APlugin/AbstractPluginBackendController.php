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
use WonderWp\Notification\AdminNotification;
use WonderWp\Services\AbstractService;
use WonderWp\Templates\VueFrag;

abstract class AbstractPluginBackendController
{


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
    public function __construct(ManagerInterface $manager)
    {

        $this->_manager = $manager;

    }

    public function getRoute()
    {
        $request = Request::getInstance();
        $action = $request->get('action', '');
        if (empty($action)) {
            $tabIndex = $request->get('tab', 1);
            $tabs = $this->getTabs();
            if (!empty($tabs[$tabIndex]) && !empty($tabs[$tabIndex]['action'])) {
                $action = $tabs[$tabIndex]['action'];
            }
            if (empty($action)) {
                $action = 'list';
            }
        }
        return $action;
    }

    public function route()
    {
        $action = $this->getRoute();
        $this->execRoute($action);
    }

    public function execRoute($action)
    {
        $action .= 'Action';

        if (method_exists($this, $action)) {
            call_user_func(array($this, $action));
        } else {
            echo "Method $action not callable on this controller";
        }
    }

    public function listAction(ListTable $listTableInstance = null)
    {
        $container = Container::getInstance();

        if (empty($listTableInstance)) {
            $listTableInstance = $this->_manager->getService(AbstractService::$LISTTABLESERVICENAME);
        }

        $entityName = $listTableInstance->getEntityName();
        if (empty($entityName)) {
            $entityName = $this->_manager->getConfig('entityName');
        }
        if (!empty($entityName)) {
            $listTableInstance->setEntityName($entityName);
        }

        $textDomain = $listTableInstance->getTextDomain();
        if (empty($textDomain)) {
            $textDomain = $this->_manager->getConfig('textDomain');
        }
        if (!empty($textDomain)) {
            $listTableInstance->setTextDomain($textDomain);
        }

        $notifications = $this->flashesToNotifications();

        $prefix = $this->_manager->getConfig('prefix');
        $vue = $container->offsetGet('wwp.views.listAdmin')
            ->registerFrags($prefix)
            ->render([
                'title' => get_admin_page_title(),
                'tabs' => $this->getTabs(),
                'listTableInstance' => $listTableInstance,
                'notifications'=>$notifications
            ]);
    }

    public function editAction($entityName = '', $modelForm = null)
    {

        $container = Container::getInstance();
        $em = $container->offsetGet('entityManager');
        $request = Request::getInstance();
        $prefix = $this->_manager->getConfig('prefix');

        //Load entity
        $id = $request->get('id', 0);
        if (empty($entityName)) {
            $entityName = $this->_manager->getConfig('entityName');
        }
        if (!empty($id)) {
            $item = $em->find($entityName, $id);
        } else {
            $item = new $entityName();
        }

        //Get new form instance
        /* @var $formInstance \WonderWp\Forms\FormInterface */
        $formInstance = $container->offsetGet('wwp.forms.form');

        //Build model form, by adding fields corresponding to the model attributes, to the form instance
        /* @var $modelForm \WonderWp\Forms\ModelForm */
        if (is_null($modelForm)) {
            $modelForm = $this->_manager->getService(AbstractService::$MODELFORMSERVICENAME);
        }
        if (is_null($modelForm)) {
            $modelForm = $container->offsetGet('wwp.forms.modelForm');
        }

        $textDomain = $modelForm->getTextDomain();
        if (empty($textDomain)) {
            $textDomain = $this->_manager->getConfig('textDomain');
        }
        if (!empty($textDomain)) {
            $modelForm->setTextDomain($textDomain);
        }

        $modelForm->setModelInstance($item);
        $modelForm->setFormInstance($formInstance)->buildForm();

        $errors = array();
        $notification = null;
        if ($request->getMethod() == 'POST') {
            $data = $request->request->all();
            /*} else {
                $data = array();
            }*/
            $formValidator = $container->offsetExists($prefix . 'wwp.forms.formValidator') ? $container->offsetGet($prefix . 'wwp.forms.formValidator') : $container->offsetGet('wwp.forms.formValidator');
            $errors = $modelForm->handleRequest($data, $formValidator);
            if (!empty($errors)) {
                $notifType = 'error';
                $notifMsg = ($id > 0) ? $container->offsetGet('wwp.element.edit.error') : $container->offsetGet('wwp.element.add.error');
            } else {
                $notifType = 'success';
                $notifMsg = ($id > 0) ? $container->offsetGet('wwp.element.edit.success') : $container->offsetGet('wwp.element.add.success');
            }
            $notification = new AdminNotification($notifType, $notifMsg);

        }

        $formInstance = $modelForm->getFormInstance();

        //Form View
        /* @var $formView \WonderWp\Forms\FormViewInterface */
        $formView = $container->offsetGet('wwp.forms.formView');
        $formView->setFormInstance($formInstance);

        $vue = $container->offsetGet('wwp.views.editAdmin')
            ->registerFrags($prefix)
            ->render([
                'title' => get_admin_page_title(),
                'tabs' => $this->getTabs(),
                'formView' => $formView,
                'formSubmitted' => ($request->getMethod() == 'POST'),
                'formValid' => (empty($errors)),
                'notification' => $notification
            ]);

    }

    public function deleteAction()
    {
        $container = Container::getInstance();
        $em = $container->offsetGet('entityManager');
        $request = Request::getInstance();

        //Load entity
        $id = $request->get('id', 0);
        $prefix = $this->_manager->getConfig('prefix');
        $entityName = $this->_manager->getConfig('entityName');

        if (!empty($id)) {
            $item = $em->find($entityName, $id);
            $em->remove($item);
            $em->flush();
            $request->getSession()->getFlashbag()->add('success',$container->offsetGet('wwp.element.delete.success'));
        } else {
            $request->getSession()->getFlashbag()->add('success',$container->offsetGet('wwp.element.delete.error'));
        }
        $request->query->remove('action');
        $request->query->remove('id');

        \WonderWp\redirect($request->getBaseUrl() . '?' . http_build_query($request->query->all()));
    }

    public function getTabs()
    {

    }

    public function getMinCapability()
    {
        return 'read';
    }

    public function flashesToNotifications(){
        $request = Request::getInstance();
        $flashes = $request->getSession()->getFlashbag()->all();
        $notifications = array();

        if(!empty($flashes)){ foreach ($flashes as $type=>$messages){
            if(!empty($messages)){ foreach ($messages as $message){
                $notification = new AdminNotification($type,$message);
                $notifications[] = $notification->getMarkup();
            }}
        }}
        return $notifications;
    }
}
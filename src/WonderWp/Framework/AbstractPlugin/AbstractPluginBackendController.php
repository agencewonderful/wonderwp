<?php

namespace WonderWp\Framework\AbstractPlugin;

use WonderWp\Framework\DependencyInjection\Container;
use WonderWp\Framework\Forms\FormInterface;
use WonderWp\Framework\Forms\FormViewInterface;
use WonderWp\Framework\Forms\ModelForm;
use WonderWp\Framework\HttpFoundation\Request;
use WonderWp\Framework\Notification\AdminNotification;
use WonderWp\Framework\Services\ServiceInterface;

abstract class AbstractPluginBackendController
{
    /** @var ManagerInterface */
    protected $manager;

    /**
     * AbstractPluginBackendController constructor.
     *
     * @param ManagerInterface $manager
     */
    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        $request = Request::getInstance();
        $action  = $request->get('action');

        if ($action !== null) {
            return $action;
        }

        $tabIndex = $request->get('tab', 1);
        $tabs     = $this->getTabs();
        if (array_key_exists($tabIndex, $tabs) && is_array($tabs[$tabIndex]) && array_key_exists('action', $tabs[$tabIndex])) {
            return $tabs[$tabIndex]['action'];
        }

        return $this->getDefaultRoute();
    }

    /**
     * @return string
     */
    public function getDefaultRoute()
    {
        return 'default';
    }

    /**
     * @return void
     */
    public function defaultAction()
    {
        $this->listAction();
    }

    /**
     * @return void
     */
    public function route()
    {
        $action = $this->getRoute();
        $this->execRoute($action);
    }

    /**
     * @param string $action
     *
     * @return void
     */
    public function execRoute($action)
    {
        $action .= 'Action';

        if (method_exists($this, $action)) {
            call_user_func([$this, $action]);
        } else {
            echo "Method $action not callable on this controller";
        }
    }

    /**
     * @param AbstractListTable $listTableInstance
     */
    public function listAction(AbstractListTable $listTableInstance = null)
    {
        $container = Container::getInstance();

        if (empty($listTableInstance)) {
            $listTableInstance = $this->manager->getService(ServiceInterface::LIST_TABLE_SERVICE_NAME);
        }

        $listTableInstance = $this->getListTableInstance($listTableInstance);

        $notifications = $this->flashesToNotifications();

        $prefix = $this->manager->getConfig('prefix');
        $container
            ->offsetGet('wwp.views.listAdmin')
            ->registerFrags($prefix)
            ->render([
                'title'             => get_admin_page_title(),
                'tabs'              => $this->getTabs(),
                'listTableInstance' => $listTableInstance,
                'notifications'     => $notifications,
            ])
        ;
    }

    /**
     * @param AbstractListTable|null $listTable
     *
     * @return AbstractListTable
     */
    protected function getListTableInstance(AbstractListTable $listTable = null)
    {
        if ($listTable === null) {
            $listTable = $this->manager->getService(ServiceInterface::LIST_TABLE_SERVICE_NAME);
        }


        if (!$listTable instanceof AbstractListTable) {
            return null;
        }

        if (empty($listTable->getTextDomain()) && !empty($textDomain = $this->manager->getConfig('textDomain'))) {
            $listTable->setTextDomain($textDomain);
        }

        return $listTable;
    }

    /**
     * @param string $entityName
     * @param null   $modelForm
     */
    public function editAction($entityName = '', $modelForm = null)
    {
        $container = Container::getInstance();
        $request   = Request::getInstance();
        $em        = $container->offsetGet('entityManager');
        $prefix    = $this->manager->getConfig('prefix');

        //Load entity
        $id = $request->get('id', 0);
        if (empty($entityName)) {
            $entityName = $this->manager->getConfig('entityName');
        }
        if (!empty($id)) {
            $item = $em->find($entityName, $id);
        } else {
            $item = new $entityName();
        }

        //Get new form instance
        /* @var $formInstance FormInterface */
        $formInstance = $container->offsetGet('wwp.forms.form');

        //Build model form, by adding fields corresponding to the model attributes, to the form instance
        /* @var $modelForm ModelForm */
        if ($modelForm === null) {
            $modelForm = $this->manager->getService(ServiceInterface::MODEL_FORM_SERVICE_NAME);
        }
        if ($modelForm === null) {
            $modelForm = $container->offsetGet('wwp.forms.modelForm');
        }

        //load textdomain
        $textDomain = $modelForm->getTextDomain();
        if (empty($textDomain)) {
            $textDomain = $this->manager->getConfig('textDomain');
        }
        if (!empty($textDomain)) {
            $modelForm->setTextDomain($textDomain);
        }

        //Set Model instance
        $modelForm->setModelInstance($item);

        //Set form instance, then build form from model attributes and groups
        $modelForm->setFormInstance($formInstance)->buildForm();

        $errors       = [];
        $notification = null;
        if ($request->getMethod() == 'POST') {
            $data = $request->request->all();
            /*} else {
                $data = array();
            }*/

            $formValidator = $container->offsetExists($prefix . 'wwp.forms.formValidator') ? $container->offsetGet($prefix . 'wwp.forms.formValidator')
                : $container->offsetGet('wwp.forms.formValidator');
            $errors        = $modelForm->handleRequest($data, $formValidator);
            if (!empty($errors)) {
                $notifType = 'error';
                $notifMsg  = ($id > 0) ? $container->offsetGet('wwp.element.edit.error') : $container->offsetGet('wwp.element.add.error');
            } else {
                $notifType = 'success';
                $notifMsg  = ($id > 0) ? $container->offsetGet('wwp.element.edit.success') : $container->offsetGet('wwp.element.add.success');
            }
            $notification = new AdminNotification($notifType, $notifMsg);
        }

        $formInstance = $modelForm->getFormInstance();

        //Form View
        /* @var FormViewInterface $formView */
        $formView = $container->offsetGet('wwp.forms.formView');
        $formView->setFormInstance($formInstance);

        $container->offsetGet('wwp.views.editAdmin')
                  ->registerFrags($prefix)
                  ->render([
                      'title'         => get_admin_page_title(),
                      'tabs'          => $this->getTabs(),
                      'formView'      => $formView,
                      'formSubmitted' => ($request->getMethod() == 'POST'),
                      'formValid'     => (empty($errors)),
                      'notification'  => $notification,
                  ])
        ;

    }

    public function deleteAction()
    {
        $container = Container::getInstance();
        $em        = $container->offsetGet('entityManager');
        $request   = Request::getInstance();

        //Load entity
        $id         = $request->get('id', 0);
        $entityName = $this->manager->getConfig('entityName');

        if (!empty($id)) {
            $item = $em->find($entityName, $id);
            $em->remove($item);
            $em->flush();
            $request->getSession()->getFlashbag()->add('success', $container->offsetGet('wwp.element.delete.success'));
        } else {
            $request->getSession()->getFlashbag()->add('success', $container->offsetGet('wwp.element.delete.error'));
        }
        $request->query->remove('action');
        $request->query->remove('id');

        \WonderWp\Framework\redirect($request->getBaseUrl() . '?' . http_build_query($request->query->all()));
    }

    /**
     * @return array
     */
    public function getTabs()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getMinCapability()
    {
        return 'read';
    }

    /**
     * @return string[]
     */
    public function flashesToNotifications()
    {
        $request       = Request::getInstance();
        $flashes       = $request->getSession()->getFlashbag()->all();
        $notifications = [];

        foreach ($flashes as $type => $messages) {
            foreach ($messages as $message) {
                $notification    = new AdminNotification($type, $message);
                $notifications[] = $notification->getMarkup();
            }
        }

        return $notifications;
    }

    /**
     * @return ManagerInterface
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param ManagerInterface $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }
}

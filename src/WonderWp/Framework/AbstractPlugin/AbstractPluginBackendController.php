<?php

namespace WonderWp\Framework\AbstractPlugin;

use WonderWp\Framework\DependencyInjection\Container;
use WonderWp\Framework\HttpFoundation\Request;
use WonderWp\Framework\Notification\AdminNotification;
use WonderWp\Framework\Service\ServiceInterface;

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
        $container = Container::getInstance();
        $prefix    = $this->manager->getConfig('prefix');
        $container
            ->offsetGet('wwp.views.baseAdmin')
            ->registerFrags($prefix)
            ->render([
                'title' => get_admin_page_title(),
                'tabs'  => $this->getTabs(),
            ])
        ;
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

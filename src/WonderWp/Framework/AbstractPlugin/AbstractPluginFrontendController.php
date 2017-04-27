<?php

namespace WonderWp\Framework\AbstractPlugin;

use WonderWp\Framework\DependencyInjection\Container;
use function WonderWp\Framework\get_plugin_file;

abstract class AbstractPluginFrontendController
{
    /** @var Container */
    protected $container;
    /** @var ManagerInterface */
    protected $manager;

    /**
     * AbstractPluginFrontendController constructor.
     *
     * @param ManagerInterface $manager
     */
    public function __construct(ManagerInterface $manager)
    {
        $this->manager   = $manager;
        $this->container = Container::getInstance();
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function handleShortCode(array $attributes = [])
    {
        if (!empty($attributes['action']) && method_exists($this, $attributes['action'] . 'Action')) {
            return call_user_func_array([$this, $attributes['action'] . 'Action'], [$attributes]);
        } else {
            return $this->defaultAction($attributes);
        }
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function defaultAction(array $attributes = [])
    {
        return '';
    }

    /**
     * @param string $viewName
     * @param array  $params
     *
     * @return string
     */
    public function renderView($viewName, array $params = [])
    {
        $viewContent = '';
        $pluginRoot  = $this->manager->getConfig('path.root');

        if (!empty($pluginRoot)) {
            $viewFile = get_plugin_file($pluginRoot, DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $viewName . '.php');
            if (!file_exists($viewFile)) {
                $viewFile = $pluginRoot . '/public/views/' . $viewName . '.php';
            }

            if (file_exists($viewFile)) {
                ob_start();
                // Spread attributes
                extract($params);
                include $viewFile;
                return ob_get_clean();
            }
        }

        return $viewContent;
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

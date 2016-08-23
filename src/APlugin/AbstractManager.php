<?php

namespace WonderWp\APlugin;

use WonderWp\DI\Container;
use Pimple\Container as PContainer;
use WonderWp\HttpFoundation\Request;

abstract class AbstractManager implements ManagerInterface
{

    protected $_container;
    protected $_request;
    protected $_router;

    public function __construct(Container $container = null)
    {
        $this->_container = $container instanceof Container ? $container : Container::getInstance();

        $autoLoader = $this->_container->offsetGet('wwp.autoLoader');
        $this->autoLoad($autoLoader);

        $this->register($this->_container);
    }

    public function run()
    {
        $this->_request = Request::getInstance();
        $this->_router = $this->getRouter();

        add_action('init', array($this, 'registerAssetService'));
    }

    public function getRouter()
    {
        return null;
    }

    public function getAssetService()
    {
        return null;
    }

    public function registerAssetService()
    {
        /** @var ThemeAssetService $assetService */
        $assetService = $this->getAssetService();

        if (is_object($assetService)) {
            /** @var AssetManager $assetManager */
            $assetManager = $this->_container->offsetGet('wwp.assets.manager');

            $assetManager->addAssetService($assetService);
        }
    }


}
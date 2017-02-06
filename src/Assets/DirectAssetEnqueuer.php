<?php

namespace WonderWp\Assets;

class DirectAssetEnqueuer extends AbstractAssetEnqueuer
{

    /**
     * @var AssetManager
     */
    protected $_assetsManager;

    public function __construct()
    {
        parent::__construct();
        $this->_assetsManager = $this->_container->offsetGet('wwp.assets.manager');
        $this->_assetsManager->callServices();
    }

    public function enqueueStyles($groupNames)
    {
        $toRender = $this->_assetsManager->getDependencies('css');

        if (!empty($toRender)) {
            foreach ($toRender as $dep) {
                /* @var $dep Asset */
                if(in_array($dep->concatGroup,$groupNames)) {
                    wp_enqueue_style($dep->handle, $dep->src, $dep->deps, $dep->ver);
                }
            }
        }

    }

    public function enqueueScripts($groupNames)
    {

    }

    public function enqueueCritical($groupNames)
    {

    }

}

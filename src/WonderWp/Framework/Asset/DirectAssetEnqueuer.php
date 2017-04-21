<?php

namespace WonderWp\Framework\Asset;

class DirectAssetEnqueuer extends AbstractAssetEnqueuer
{
    /** @var AssetManager */
    protected $assetsManager;

    /** @inheritdoc */
    public function __construct()
    {
        parent::__construct();

        $this->assetsManager = $this->container['wwp.assets.manager'];
        $this->assetsManager->callServices();
    }

    /** @inheritdoc */
    public function enqueueStyles(array $groupNames)
    {
        $toRender = $this->assetsManager->getDependencies('css');

        foreach ($toRender as $dep) {
            /* @var $dep Asset */
            if (in_array($dep->concatGroup, $groupNames)) {
                wp_enqueue_style($dep->handle, $dep->src, $dep->deps, $dep->ver);
            }
        }
    }

    /** @inheritdoc */
    public function enqueueScripts(array $groupNames) { }

    /** @inheritdoc */
    public function enqueueCritical(array $groupNames) { }
}

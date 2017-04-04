<?php

namespace WonderWp\Framework\Assets;

interface AssetEnqueuerInterface
{
    /**
     * @param array $groupNames
     */
    public function enqueueStyles(array $groupNames);

    /**
     * @param array $groupNames
     */
    public function enqueueScripts(array $groupNames);

    /**
     * @param array $groupNames
     */
    public function enqueueCritical(array $groupNames);
}

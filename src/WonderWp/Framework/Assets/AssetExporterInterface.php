<?php

namespace WonderWp\Framework\Assets;

interface AssetExporterInterface
{
    /**
     * @param $args
     */
    public function __invoke($args);

    public function export();
}

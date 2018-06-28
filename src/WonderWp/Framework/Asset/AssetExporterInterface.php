<?php

namespace WonderWp\Framework\Asset;

interface AssetExporterInterface
{
    /**
     * @param $args
     */
    public function __invoke($args);

    public function export();
}

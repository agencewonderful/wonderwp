<?php

namespace WonderWp\Assets;

interface AssetExporterInterface{

    public function __invoke($args);

    public function export();

}

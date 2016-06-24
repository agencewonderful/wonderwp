<?php

namespace WonderWp\Assets;

class AssetsExporter implements AssetsExporterInterface{

    public function __invoke($args)
    {
        WP_CLI::success( $args[0] );
    }

}
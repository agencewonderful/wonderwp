<?php

namespace WonderWp\Assets;

use WonderWp\DI\Container;
use Pimple\Container as PContainer;
use WP_CLI;

abstract class AbstractAssetExporter implements AssetExporterInterface{

    /**
     * @var PContainer
     */
    protected $_container;

    public function __invoke($args)
    {
        $this->_container = Container::getInstance();
        $this->export();
    }

    public function respond($res){
        if(is_array($res) && $res['code'] && $res['code']==200){
            WP_CLI::success($res['data']['msg']);
        } else {
            $errorMsg = (is_array($res) && $res['data'] && $res['data']['msg']) ? $res['data']['msg'] : 'error';
             WP_CLI::error($errorMsg);
        }
    }

}
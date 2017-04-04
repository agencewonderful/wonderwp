<?php

namespace WonderWp\Framework\Assets;

use WonderWp\Framework\DependencyInjection\Container;
use WP_CLI;

abstract class AbstractAssetExporter implements AssetExporterInterface
{
    /** @var Container */
    protected $container;

    /** @inheritdoc */
    public function __invoke($args)
    {
        $this->container = Container::getInstance();
        $this->export();
    }

    /**
     * @param $res
     */
    public function respond($res)
    {
        if (is_array($res) && $res['code'] && $res['code'] == 200) {
            WP_CLI::success($res['data']['msg']);
        } else {
            $errorMsg = (is_array($res) && $res['data'] && $res['data']['msg']) ? $res['data']['msg'] : 'error';
            WP_CLI::error($errorMsg);
        }
    }
}

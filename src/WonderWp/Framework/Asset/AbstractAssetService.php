<?php

namespace WonderWp\Framework\Asset;

abstract class AbstractAssetService implements AssetServiceInterface
{
    const VENDOR_ASSETS_GROUP = 'vendor';
    const PUBLIC_ASSETS_GROUP = 'app';
    const CORE_ASSETS_GROUP = 'core';
    const PLUGINS_ASSETS_GROUP = 'plugins';
    const STYLEGUIDE_ASSETS_GROUP = 'styleguide';
    const BOOTSTRAP_ASSETS_GROUP = 'bootstrap';
    const ADMIN_ASSETS_GROUP  = 'admin';

    protected $assets = [];
}

<?php

namespace WonderWp\Framework\Assets;

abstract class AbstractAssetService implements AssetServiceInterface
{
    const PUBLIC_ASSETS_GROUP = 'app';
    const ADMIN_ASSETS_GROUP  = 'admin';

    protected $assets = [];
}

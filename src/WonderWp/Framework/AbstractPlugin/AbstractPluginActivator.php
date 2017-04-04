<?php

namespace WonderWp\Framework\AbstractPlugin;

abstract class AbstractPluginActivator implements ActivatorInterface
{
    /** @var string */
    protected $version;

    /**
     * @param string $version
     */
    public function __construct($version)
    {
        $this->version = $version;
    }

    /** @inheritdoc */
    public function activate()
    {
    }
}

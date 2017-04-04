<?php

namespace WonderWp\Framework\HttpFoundation;

use Symfony\Component\HttpFoundation\Request as BaseRequest;
use Symfony\Component\HttpFoundation\Session\Session;
use WonderWp\Framework\DependencyInjection\SingletonInterface;
use WonderWp\Framework\DependencyInjection\SingletonTrait;

class Request extends BaseRequest implements SingletonInterface
{
    use SingletonTrait {
        SingletonTrait::buildInstance as createInstance;
    }

    /** @inheritdoc */
    public static function getInstance()
    {
        $instance = static::createFromGlobals();
        $instance->setSession(new Session());

        return $instance;
    }
}

<?php

namespace WonderWp\Framework\DependencyInjection;

trait SingletonTrait
{
    /** @var SingletonTrait */
    private static $singletonInstance;

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (static::$singletonInstance === null) {
            static::$singletonInstance = static::buildInstance();
        }

        return static::$singletonInstance;
    }

    /**
     * @param SingletonInterface $instance
     *
     * @return void
     */
    public static function setInstance(SingletonInterface $instance)
    {
        self::$singletonInstance = $instance;
    }

    /**
     * @return static
     */
    public static function buildInstance()
    {
        return new static();
    }
}

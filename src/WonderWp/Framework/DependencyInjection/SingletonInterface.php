<?php

namespace WonderWp\Framework\DependencyInjection;

interface SingletonInterface
{
    /**
     * @return static
     */
    public static function getInstance();

    /**
     * @param SingletonInterface $instance
     *
     * @return void
     */
    public static function setInstance(SingletonInterface $instance);

    /**
     * @return static
     */
    public static function buildInstance();
}

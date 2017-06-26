<?php

namespace WonderWp\Framework\Route;

/**
 * With a route service, you can define two kinds of routes:
 * - Routes that map a url to a certain file
 * - Routes that map a url to a certain callable
 * You can also restrict the authorized HTTP methods that can call this route
 */
class Route
{
    /**
     * regex that specifies the route condition
     * @var string
     */
    protected $path;
    /**
     * Callable that will be executed upon route validation
     * @var callable
     */
    protected $callable;
    /**
     * The HTTP method authorized to call this route
     * @var string
     */
    protected $method = 'ALL';

    /**
     * @param $args
     * @codeCoverageIgnore
     */
    public function __construct($args)
    {
        $this->path     = !empty($args[0]) ? $args[0] : null;
        $this->callable = !empty($args[1]) ? $args[1] : null;
        $this->method   = !empty($args[2]) ? $args[2] : 'ALL';

        return $this;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @codeCoverageIgnore
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return callable
     * @codeCoverageIgnore
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * @param callable $callable
     * @codeCoverageIgnore
     */
    public function setCallable($callable)
    {
        $this->callable = $callable;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @codeCoverageIgnore
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }
}

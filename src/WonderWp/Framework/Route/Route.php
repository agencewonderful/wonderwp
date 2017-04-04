<?php

namespace WonderWp\Framework\Route;

/**
 * Class Route
 * With a route service, you can define two kinds of routes:
 * - Routes that map a url to a certain file
 * - Routes that map a url to a certain callable
 * You can also restrict the authorized HTTP methods that can call this route
 * @package WonderWp\Route
 */
class Route
{
    /**
     * regex that specifies the route condition
     * @var string
     */
    private $_path;
    /**
     * Callable that will be executed upon route validation
     * @var callable
     */
    private $_callable;
    /**
     * The HTTP method authorized to call this route
     * @var string
     */
    private $_method = 'ALL';

    /**
     * Route constructor.
     *
     * @param $args
     */
    public function __construct($args)
    {
        $this->_path     = !empty($args[0]) ? $args[0] : null;
        $this->_callable = !empty($args[1]) ? $args[1] : null;
        $this->_method   = !empty($args[2]) ? $args[2] : 'ALL';

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->_path = $path;
    }

    /**
     * @return callable
     */
    public function getCallable()
    {
        return $this->_callable;
    }

    /**
     * @param callable $callable
     */
    public function setCallable($callable)
    {
        $this->_callable = $callable;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->_method = $method;
    }

}

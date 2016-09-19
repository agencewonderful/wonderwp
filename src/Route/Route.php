<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 26/08/2016
 * Time: 16:14
 */
namespace WonderWp\Route;

class Route
{
    private $_path;
    private $_callable;
    private $_method = 'ALL';

    /**
     * Route constructor.
     * @param $args
     */
    public function __construct($args)
    {
        $this->_path = !empty($args[0]) ? $args[0] : null;
        $this->_callable = !empty($args[1]) ? $args[1] : null;
        $this->_method = !empty($args[2]) ? $args[2] : 'ALL';
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->_path = $path;
    }

    /**
     * @return mixed
     */
    public function getCallable()
    {
        return $this->_callable;
    }

    /**
     * @param mixed $callable
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
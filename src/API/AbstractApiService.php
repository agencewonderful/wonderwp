<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 25/08/2016
 * Time: 17:03
 */

namespace WonderWp\API;

use WonderWp\HttpFoundation\Request;
use WonderWp\Services\AbstractService;

abstract class AbstractApiService extends AbstractService implements ApiServiceInterface{

    /** @var \WonderWp\HttpFoundation\Request */
    protected $_request;

    public function registerEndpoints()
    {
        $exclude = array('__construct','registerEndpoints');
        $className = (new \ReflectionClass($this))->getShortName();
        $methods = array_diff(get_class_methods($this),$exclude);

        if(!empty($methods)){ foreach ($methods as $method){

            $callable = new \stdClass();
            $callable->instance=$this;
            $callable->method=$method;

            add_action( 'wp_ajax_'.$className.'.'.$method, function() use ($callable){
                $callable->instance->setRequest(Request::getInstance());
                call_user_func(array($callable->instance,$callable->method));
                wp_die();
            });
        }}
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->_request = $request;
    }

    public function respond(Result $result,$format='json'){
        if($format=='json') {
            header('Content-Type: application/json');
            echo $result;
        }
        return $result;
    }

    public function requestParameter($paramName='all'){
        if($paramName=='all') {
            return $this->_request->request->all();
        } else {
            return $this->_request->request->get($paramName);
        }
    }

}

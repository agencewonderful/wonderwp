<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 26/08/2016
 * Time: 16:14
 */
namespace WonderWp\Route;

use WonderWp\AbstractDefinitions\Singleton;
use WonderWp\Route\RouterInterface;

class Router extends AbstractRouter
{

    protected $_routes = array();
    protected $_services = array();
    protected $_routeVariable = 'route';
    /**
     * @var Route
     */
    protected $_matchedRoute;

    public function __construct()
    {
        add_action('init', array($this, 'registerRules'));
        add_action('admin_init', array($this,'flushRules'));
        add_action('parse_request', array($this, 'match_request'));
        add_action('template_redirect', array($this, 'call_route_hook'));
    }

    public function addService(RouteServiceInterface $routeService)
    {
        $this->_services[] = $routeService;
    }

    public function getRoutes()
    {
        if (!empty($this->_services)) {
            foreach ($this->_services as $service) {
                /** @var RouteServiceInterface $service */
                $serviceName = get_class($service);
                $serviceRoutes = $service->getRoutes();
                if (!empty($serviceRoutes)) {
                    foreach ($serviceRoutes as $i => $r) {
                        if(is_array($r)){
                            $r = new Route($r);
                        }
                        $this->_routes[sanitize_title($serviceName .'#'. $i)] = $r;
                    }
                }
            }
        }
        return $this->_routes;
    }

    public function registerRules()
    {
        $routes = $this->getRoutes();
        if (!empty($routes)) {
            add_rewrite_tag('%'.$this->_routeVariable.'%', '(.+)');
            foreach ($routes as $name => $route) {
                /** @var Route $route */
                $regex = $this->generate_route_regex($route);
                add_rewrite_rule($regex,  'index.php?'.$this->_routeVariable.'='.$name, 'top');
            }
        }
    }

    /**
     * Generates the regex for the WordPress rewrite API for the given route.
     *
     * @param Route $route
     *
     * @return string
     */
    private function generate_route_regex(Route $route)
    {
        return '^'.ltrim(trim($route->getPath()), '/').'$';
    }

    /**
     * Attempts to match the current request to a route.
     *
     * @param WP $environment
     */
    public function match_request(\WP $environment)
    {
        $matched_route = $this->match($environment->query_vars);
        if ($matched_route instanceof Route) {
            $this->_matchedRoute = $matched_route;
        }
        if ($matched_route instanceof \WP_Error && in_array('route_not_found', $matched_route->get_error_codes())) {
            wp_die($matched_route, 'Route Not Found', array('response' => 404));
        }
    }

    public function match(array $query_variables)
    {
        if (empty($query_variables[$this->_routeVariable])) {
            return new \WP_Error('missing_route_variable');
        }
        $route_name = $query_variables[$this->_routeVariable];
        if (!isset($this->_routes[$route_name])) {
            return new \WP_Error('route_not_found');
        }
        return $this->_routes[$route_name];
    }

    /**
     * Checks to see if a route was found. If there's one, it calls the route hook.
     */
    public function call_route_hook()
    {
        if(!empty($this->_matchedRoute)){
            call_user_func($this->_matchedRoute->getCallable());
        }
    }


}
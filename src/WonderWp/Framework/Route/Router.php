<?php

namespace WonderWp\Framework\Route;

use WonderWp\Framework\HttpFoundation\Request;

class Router extends AbstractRouter
{

    /**
     * @var Route[]
     */
    protected $_routes        = [];
    /**
     * @var RouteServiceInterface[]
     */
    protected $_services      = [];
    /**
     * @var string
     */
    protected $_routeVariable = 'route';
    /**
     * @var Route
     */
    protected $_matchedRoute;
    /**
     * @var array
     */
    protected $_matchedRouteParams = [];

    /** Construct */
    public function __construct()
    {
        add_action('init', [$this, 'registerRules']);
        add_action('admin_init', [$this, 'flushRules']);
        add_action('parse_request', [$this, 'matchRequest']);
        add_action('template_redirect', [$this, 'callRouteHook']);
        add_filter('query_vars', [$this, 'registerQueryVars']);
    }

    /**
     * Each plugin's own routing service is registered towards this router instance by calling this method
     * @param RouteServiceInterface $routeService
     */
    public function addService(RouteServiceInterface $routeService)
    {
        $this->_services[] = $routeService;
    }

    /**
     * Ask each routing service for their routes
     * @return Route[]
     */
    public function getRoutes()
    {
        if (!empty($this->_services)) {
            foreach ($this->_services as $service) {
                /** @var RouteServiceInterface $service */
                $serviceName   = get_class($service);
                $serviceRoutes = $service->getRoutes();
                if (!empty($serviceRoutes)) {
                    foreach ($serviceRoutes as $i => $r) {
                        if (is_array($r)) {
                            $r = new Route($r);
                        }
                        $this->_routes[sanitize_title($serviceName . '#' . $i)] = $r;
                    }
                }
            }
        }

        return $this->_routes;
    }

    /**
     * Routes are then registered to WordPress
     * @return $this
     */
    public function registerRules()
    {

        $routes = $this->getRoutes();
        if (!empty($routes)) {
            add_rewrite_tag('%' . $this->_routeVariable . '%', '(.+)');
            foreach ($routes as $name => $route) {
                /** @var Route $route */
                $regex = $this->generateRouteRegex($route);
                $path  = $route->getPath();

                $qs = $this->_routeVariable . '=' . $name;
                if (strpos($path, '{') !== false) {
                    preg_match_all('/{(.*?)}/', $path, $wildCardsMatchs);
                    $wildCards = $wildCardsMatchs[1];
                    if (!empty($wildCards)) {
                        $cpt = 1;
                        foreach ($wildCards as $wildCard) {
                            $qs .= '&' . $wildCard . '=$matches[' . $cpt . ']';
                            $cpt++;
                        }
                    }
                }
                if (is_callable($route->getCallable())) {
                    $newRewriteRule = 'index.php?' . $qs;
                } else {
                    $newRewriteRule = $route->getCallable();
                    if (strpos($newRewriteRule, $this->_routeVariable . '=' . $name) === false) {
                        $newRewriteRule .= '&' . $this->_routeVariable . '=' . $name;
                    }
                }

                add_rewrite_rule($regex, $newRewriteRule, 'top');
            }
        }

        return $this;
    }

    /**
     * Make wildcards known from WordPress
     * @param array $vars
     *
     * @return array
     */
    public function registerQueryVars($vars)
    {
        $routes = $this->getRoutes();
        if (!empty($routes)) {
            foreach ($routes as $route) {
                $path = $route->getPath();
                if (strpos($path, '{') !== false) {
                    preg_match_all('/{(.*?)}/', $path, $wildCardsMatchs);
                    if (!empty($wildCardsMatchs[1])) {
                        $vars = array_merge($vars, $wildCardsMatchs[1]);
                    }
                }
            }
        }

        return $vars;
    }

    /**
     * Generates the regex for the WordPress rewrite API for the given route.
     *
     * @param Route $route
     *
     * @return string
     */
    private function generateRouteRegex(Route $route)
    {
        $path = preg_replace('/{(.*?)}/', '(.*)', $route->getPath());

        return '^' . ltrim(trim($path), '/') . '$';
    }

    /**
     * Attempts to match the current request to a route.
     *
     * @param \WP $environment
     */
    public function matchRequest(\WP $environment)
    {
        $matched_route = $this->match($environment->query_vars);

        if ($matched_route instanceof Route) {
            $this->_matchedRoute = $matched_route;
            //Add query vars to request object
            $path    = $this->_matchedRoute->getPath();
            $request = Request::getInstance();
            if (strpos($path, '{') !== false) {
                preg_match_all('/{(.*?)}/', $path, $wildCardsMatchs);
                if (!empty($wildCardsMatchs[1])) {
                    foreach ($wildCardsMatchs[1] as $wildCard) {
                        //Passing them to the request
                        $request->query->set($wildCard, $environment->query_vars[$wildCard]);
                        //Keeping them for the callable
                        $this->_matchedRouteParams[$wildCard] = $environment->query_vars[$wildCard];
                    }
                }
            }
        }
        if ($matched_route instanceof \WP_Error) {
            if (in_array('route_not_found', $matched_route->get_error_codes())) {
                wp_redirect('/404');
            }
            if (in_array('method_not_authorized', $matched_route->get_error_codes())) {
                wp_redirect('/503');
            }
        }
    }

    /**
     * @param array $query_variables
     *
     * @return Route|\WP_Error
     */
    public function match(array $query_variables)
    {
        //Check Route Variable
        if (empty($query_variables[$this->_routeVariable])) {
            return new \WP_Error('missing_route_variable');
        }
        //Check Route
        $route_name = $query_variables[$this->_routeVariable];
        if (!isset($this->_routes[$route_name])) {
            return new \WP_Error('route_not_found');
        }
        //Check Route Method
        /** @var Route $route */
        $route         = $this->_routes[$route_name];
        $routeMethod   = $route->getMethod();
        $requestMethod = Request::getInstance()->getMethod();
        if ($routeMethod != 'ALL' && $routeMethod !== $requestMethod) {
            return new \WP_Error('method_not_authorized');
        }

        return $route;
    }

    /**
     * Checks to see if a route was found. If there's one, it calls the route hook.
     */
    public function callRouteHook()
    {
        if (!empty($this->_matchedRoute)) {
            if (is_callable($this->_matchedRoute->getCallable())) {
                call_user_func_array($this->_matchedRoute->getCallable(), $this->_matchedRouteParams);
            }
        }
    }

}

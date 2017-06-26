<?php

namespace WonderWp\Framework\Route;

use WonderWp\Framework\HttpFoundation\Request;

class Router extends AbstractRouter
{
    /** @var Route[] */
    protected $routes = [];
    /** @var RouteServiceInterface[] */
    protected $services = [];
    /** @var string */
    protected $routeVariable = 'route';
    /** @var Route */
    protected $matchedRoute;
    /** @var array */
    protected $matchedRouteParams = [];

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
     * @return RouteServiceInterface[]
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param RouteServiceInterface[] $services
     *
     * @return static
     */
    public function setServices($services)
    {
        $this->services = $services;

        return $this;
    }

    /**
     * Each plugin's own routing service is registered towards this router instance by calling this method
     *
     * @param RouteServiceInterface $routeService
     */
    public function addService(RouteServiceInterface $routeService)
    {
        $this->services[] = $routeService;
    }

    /**
     * Ask each routing service for their routes
     * @return Route[]
     */
    public function getRoutes()
    {
        if (!empty($this->services)) {
            foreach ($this->services as $service) {
                /** @var RouteServiceInterface $service */
                $serviceRoutes = $service->getRoutes();
                if (!empty($serviceRoutes)) {
                    $serviceName   = get_class($service);
                    foreach ($serviceRoutes as $i => $r) {
                        if (is_array($r)) {
                            $r = new Route($r);
                        }
                        $this->routes[sanitize_title($serviceName . '#' . $i)] = $r;
                    }
                }
            }
        }

        return $this->routes;
    }

    /**
     * Routes are then registered to WordPress
     * @return $this
     */
    public function registerRules()
    {

        $routes = $this->getRoutes();
        if (!empty($routes)) {
            add_rewrite_tag('%' . $this->routeVariable . '%', '(.+)');
            foreach ($routes as $name => $route) {
                /** @var Route $route */
                $regex = $this->generateRouteRegex($route);
                $path  = $route->getPath();

                $qs = $this->routeVariable . '=' . $name;
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
                $callable = $route->getCallable();
                if (is_callable($callable) || is_array($callable)) {
                    $newRewriteRule = 'index.php?' . $qs;
                } else {
                    $newRewriteRule = $callable;
                    if (strpos($newRewriteRule, $this->routeVariable . '=' . $name) === false) {
                        $newRewriteRule .= '&' . $this->routeVariable . '=' . $name;
                    }
                }

                add_rewrite_rule($regex, $newRewriteRule, 'top');
            }
        }

        return $this;
    }

    /**
     * Make wildcards known from WordPress
     *
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
    protected function generateRouteRegex(Route $route)
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
            $this->matchedRoute = $matched_route;
            //Add query vars to request object
            $path    = $this->matchedRoute->getPath();
            $request = Request::getInstance();
            if (strpos($path, '{') !== false) {
                preg_match_all('/{(.*?)}/', $path, $wildCardsMatchs);
                if (!empty($wildCardsMatchs[1])) {
                    foreach ($wildCardsMatchs[1] as $wildCard) {
                        //Passing them to the request
                        $request->query->set($wildCard, $environment->query_vars[$wildCard]);
                        //Keeping them for the callable
                        $this->matchedRouteParams[$wildCard] = $environment->query_vars[$wildCard];
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
        if (empty($query_variables[$this->routeVariable])) {
            return new \WP_Error('missing_route_variable');
        }
        //Check Route
        $route_name = $query_variables[$this->routeVariable];
        if (!isset($this->routes[$route_name])) {
            return new \WP_Error('route_not_found');
        }
        //Check Route Method
        /** @var Route $route */
        $route         = $this->routes[$route_name];
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
        if (!empty($this->matchedRoute)) {
            if (is_callable($this->matchedRoute->getCallable())) {
                call_user_func_array($this->matchedRoute->getCallable(), $this->matchedRouteParams);
            }
        }
    }
}

<?php

namespace WonderWp\Framework\Route;

use WonderWp\Framework\AbstractPlugin\AbstractPluginManager;

/**
 * AbstractRouteService defines methods to add routes in the app.
 */
abstract class AbstractRouteService implements RouteServiceInterface
{
    /** @var array */
    protected $routes = [];

    /** @var AbstractPluginManager */
    protected $manager;

    /** @var AbstractPluginManager */
    protected $publicController;

    /**
     * Get registered routes. Must be overriden.
     *
     * @return array
     *
     * Code example:
     *
     *   public function getRoutes()
     *   {
     *       if (empty($this->routes)) {
     *           $this
     *               ->addCallableRoute('route_name_inmanager', 'controllerAction')
     *               ->addCallableRoute('/url-to-catch/{component}', 'controllerAction')
     *               ->addFileRoute('route_name_inmanager', 'file_to_redirect_to.php')
     *           ;
     *       }
     *
     *       return $this->routes;
     *   }
     */
    abstract public function getRoutes();

    /**
     * Generate an url for a given route reference and parameters.
     *
     * @param string  $routeRef can be a reference in the manager, or a pattern
     * @param array   $params   contains marker to replace in the url
     * @param string  $locale   defines the locale to use to generate the url (user locale by default)
     * @param boolean $absolute defines if url should be absolute (not by default)
     *
     * @return  string
     *
     * Code example:
     *
     * $manager = Container::getInstance()->offsetGet('manager_offset');
     * $router = $manager->getService(AbstractService::$ROUTESERVICENAME);
     *
     * return $router->generateUrl('route_name', ['component' => 'component_value']);
     */
    public function generateUrl($routeRef, array $params = [], $locale = null, $absolute = false)
    {
        // Searching for a pattern matching a locale.
        // With the given one first, then the default one. Elsewise, return empty string.
        $defaultLocale = get_locale();
        $locale        = ($locale && $locale !== $defaultLocale) ? $locale : null;
        $url           = '';
        $patterns      = $this->getPatterns($routeRef);
        if ($locale && isset($patterns[$locale])) {
            $url = $patterns[$locale];
        } elseif ($defaultLocale && isset($patterns[$defaultLocale])) {
            $url = $patterns[$defaultLocale];
        } else {
            return $url;
        }
        // Replace markers in pattern found
        foreach ($params as $search => $replace) {
            $url = str_replace('{' . $search . '}', $replace, $url);
        }
        // Making absolute url
        if (true === $absolute) {
            $url = get_bloginfo('url') . $url;
        }

        return $url;
    }

    /**
     * Add route calling a controller action.
     *
     * @param string $routeRef   can be a reference in the manager, or a pattern
     * @param string $action     is the name of the action to call
     * @param string $method     is the method allowed ("GET" by default)
     * @param mixed  $controller is the controller having the action to call
     *
     * @return static
     */
    protected function addCallableRoute($routeRef, $action, $method = 'ALL', $controller = null)
    {
        $controller = $controller ? $controller : $this->publicController;
        foreach ($this->getPatterns($routeRef) as $pattern) {
            $this->addRoute($pattern, [$controller, $action], $method);
        }

        return $this;
    }

    /**
     * Add route leading to a file.
     *
     * @param string $routeRef can be a reference in the manager, or a pattern
     * @param string $dest     is the file to point to
     * @param string $method   is the method allowed ("GET" by default)
     *
     * @return static
     */
    protected function addFileRoute($routeRef, $dest, $method = 'ALL')
    {
        foreach ($this->getPatterns($routeRef) as $pattern) {
            $this->addRoute($pattern, $dest, $method);
        }

        return $this;
    }

    /**
     * Add a route in container.
     *
     * @param string $route  can be a reference in the manager, or a pattern
     * @param mixed  $dest   can be an array containing controller and action, or a string file reference
     * @param string $method is the method allowed
     *
     * @return static
     */
    protected function addRoute($route, $dest, $method)
    {
        $this->routes[] = [ltrim($route, '/'), $dest, $method];

        return $this;
    }

    /**
     * Get url patterns for a given reference.
     *
     * @param string $routeRef can be a reference in the manager, or a pattern
     *
     * @return array
     */
    protected function getPatterns($routeRef)
    {
        $patterns = $this->manager->getConfig($routeRef);
        if (!$patterns) {
            $patterns = [(get_locale()) => $routeRef];
        }
        $patterns = is_array($patterns) ? $patterns : [$patterns];

        return $patterns;
    }
}

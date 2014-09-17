<?php
/**
 * Router Class. Gets Request and figures out {Controller->method} action to be called
 *
 * @package Kernel\Router
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace Kernel\Router;

use \Kernel\Exception\HttpNotFoundException;
use Kernel\Exception\LoaderException;
use \Kernel\Request\Request;

/**
 * Class Router
 *
 * @package Kernel\Router
 */
class Router
{
    /**
     * @var null
     */
    private static $_instance = null;

    /**
     * @var array
     */
    private $_registry = array();

    /**
     * Returns Router instance
     *
     * @return Router
     */
    public static function getInstance()
    {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Loads list of routes to the route registry
     *
     * @param array $routes
     */
    public function load(array $routes)
    {
        foreach ($routes as $route) {
            $this->_registry[$route['pattern']] = $route;
        }
    }

    /**
     * Finds route, controller and method. Calls them with actual params and returns result
     *
     * @return mixed
     * @throws \Kernel\Exception\LoaderException When Controller class was not found or doesn't implement controller interface
     * @throws \Kernel\Exception\HttpNotFoundException When Route was not found
     */
    public function processRoute()
    {
        if (!$route = $this->_findRoute(Request::getUri())) {
            throw new HttpNotFoundException('Page not found!');
        }
        if (!class_exists($route['controller'])) {
            throw new LoaderException(sprintf('Class "%s" was not found'));
        }

        $reflection = new \ReflectionClass($route['controller']);

        $interfaceName = 'Kernel\\Controller\\ControllerInterface';
        if (!$reflection->implementsInterface($interfaceName)) {
            throw new LoaderException(sprintf(
                'Class "%s" must implement interface "%s"',
                $route['controller'],
                $interfaceName
            ));
        }
        $reflectionMethod = $reflection->getMethod($route['action'].'Action');

        //Injecting get arguments to Controller

        $args = array();

        foreach ($reflectionMethod->getParameters() as $parameter) {
            $name        = $parameter->name;
            $args[$name] = isset($route['_values']) && isset($route['_values'][$name])?
                $route['_values'][$name]:
                $parameter->getDefaultValue();
        }

        $controller = new $route['controller'];

        return $reflectionMethod->invokeArgs($controller, $args);
    }

    /**
     * Constructor. Prevents from direct construct
     */
    private final function __construct()
    {
    }

    /**
     * Prevents router from cloning
     */
    private final function __clone()
    {
    }

    /**
     * Searches actual route and return it with found param's values
     *
     * @param string $uri Url for searching in routes registry
     *
     * @return bool|array
     */
    private function _findRoute($uri)
    {
        foreach ($this->_registry as $route) {
            $routeParams = $this->_prepareRoute($route['pattern'], isset($route['_requirements'])?$route['_requirements']:array());

            if (preg_match($routeParams['uri'], $uri, $match)) {
                unset($match[0]);
                if (strpos($uri, '?')!==false){
                    array_pop($match);
                }

                if (count($match)) {
                    $route['_values'] = array_combine($routeParams['params'], $match);
                }
                return $route;
            }
        }
        return false;
    }

    /**
     * Prepares route for comparing with current url and checking is this route actual
     *
     * @param string $pattern      Route Pattern
     * @param array  $requirements Route Requirements
     *
     * @return array {'uri'=>'{Processed pattern}', 'params'=>array( 'get_param_1', 'get_param_2' )}
     */
    private function _prepareRoute($pattern, $requirements = array())
    {
        $pattern = '/^'.str_replace('/', '\/', $pattern).'(\?.*)*$/';
        $params  = array();
        if (preg_match_all('/\{([\w\d_]+)\}/', $pattern, $match)) {
            $holders = $match[0];
            $params  = $match[1];

            foreach ($holders as $i => $holder) {
                $pattern = array_key_exists($params[$i], $requirements)?
                    str_replace($holders, '('.$requirements[$params[$i]].')', $pattern):
                    str_replace($holders, '([\w\_d]+)', $pattern);
            }
        }
        return array('uri' => $pattern, 'params' => $params);
    }
}
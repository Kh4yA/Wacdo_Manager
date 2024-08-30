<?php

namespace App\Router;

use App\Exceptions\RouteNotFound;

class Router
{
    private $routes = [];

    public function register(string $path, $action): void
    {
        if (is_callable($action) || is_array($action)) {
            $this->routes[$path] = $action;
        }
    }
    /**
     * Résout l'URI et exécute l'action associée
     * @param string $uri (chemin à traiter)
     * @return mixed (résultat de l'appel)
     */
    public function resolve(string $uri)
    {
        $path = explode('?', $uri)[0];

        foreach ($this->routes as $route => $action) {
            $routePattern = preg_replace('/{[^}]+}/', '([^/]+)', $route);
            $routePattern = str_replace('/', '\/', $routePattern);
            if (preg_match('/^' . $routePattern . '$/', $path, $matches)) {
                array_shift($matches);
                if (is_callable($action) && !is_array($action)) {
                    return call_user_func_array($action, $matches);
                }
                if (is_array($action) && count($action) === 2) {
                    list($className, $method) = $action;
                    if (class_exists($className) && method_exists($className, $method)) {
                        $class = new $className();
                        return call_user_func_array([$class, $method], $matches);
                    }
                }
            }
        }
        throw new RouteNotFound();
        
    }
}

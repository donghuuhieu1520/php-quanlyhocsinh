<?php


namespace App\Helper;

class Router
{
  private array $routes;

  public function __construct()
  {
    $this->routes = [];
  }

  private function _addToRoute(string $method, string $uri, string $handler, string $name)
  {
    if (Alfred::isValidIndentifier($name)) {
      $this->routes[$name] = [$method, $uri, explode('@', $handler)];
    } else {
      $this->routes[] = [$method, $uri, explode('@', $handler)];
    }
    return $this;
  }

  public function get(string $uri, string $handler, string $name = '')
  {
    return $this->_addToRoute("GET", $uri, $handler, $name);
  }

  public function post(string $uri, string $handler, string $name = '')
  {
    return $this->_addToRoute("POST", $uri, $handler, $name);
  }

  public function getNames()
  {
    return array_map(function ($route) {
      return $route[1];
    }, $this->getRoutes());
  }

  public function getRoutes()
  {
    // Remove trailing slash
    return array_map(function ($route) {
      if (strlen($route[1]) !== 1 && substr($route[1], -1) === '/') {
        $route[1] = substr($route[1], 0, strlen($route[1]) - 1);
        $this->routes[] = $route;
      }
      return $route;
    }, $this->routes);
  }

  public function useRoute(string $uri, Router $router)
  {
    $this->routes = array_merge(
        $this->routes,
        array_map(function ($route) use ($uri) {
          $slash = '/';
          if (substr($route[1], 0, 1) === '/' ||
            substr($uri, -1) === '/') {
          $slash = '';
          }
          $route[1] = $uri . $slash . $route[1];
          return $route;
        }, $router->getRoutes())
    );
    return $this;
  }
}
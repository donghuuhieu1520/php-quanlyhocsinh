<?php


namespace App\Helper;


class Router
{
  private array $routes;

  public function __construct()
  {
    $this->routes = [];
  }

  public function get(string $uri, string $handler)
  {
    $this->routes[] = ['GET', $uri, explode('@', $handler)];
    return $this;
  }

  public function post(string $uri, string $handler)
  {
    $this->routes[] = ['POST', $uri, explode('@', $handler)];
    return $this;
  }

  public function getRoutes()
  {
    // Remove trailing slash
    return array_map(function ($route) {
      if (substr($route[1], -1) === '/') {
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
<?php


namespace App\Helper;


class Router
{
  private static array $routes = [];
  public static function get(string $uri, string $handler)
  {
    self::$routes[] = ['GET', $uri, explode('@', $handler)];
  }
  public static function post(string $uri, string $handler)
  {
    self::$routes[] = ['POST', $uri, explode('@', $handler)];
  }

  public static function getRoutes()
  {
    return self::$routes;
  }
}
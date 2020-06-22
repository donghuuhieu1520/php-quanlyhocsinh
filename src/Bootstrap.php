<?php declare(strict_types = 1);

namespace App;

use App\Helper\Router;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use function FastRoute\simpleDispatcher;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');
$environment = $_ENV['ENVIRONMENT'];

/**
* Register the error handler
*/
$whoops = new Run;
if ($environment !== 'production') {
    $whoops->pushHandler(new PrettyPageHandler);
} else {
    $whoops->pushHandler(function($e){
        echo 'Todo: Friendly error page and send an email to the developer';
    });
}
$whoops->register();

$injector = include('Dependencies.php');

$request = $injector->make('Http\HttpRequest');
$response = $injector->make('Http\HttpResponse');

$router = $injector->make('App\Helper\Router');
$routes = $router->getRoutes();
$dispatcher = simpleDispatcher(function (RouteCollector $r) use ($routes) {
  foreach ($routes as $route) {
    $r->addRoute($route[0], $route[1], $route[2]);
  }
});

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());

switch ($routeInfo[0]) {
  case Dispatcher::NOT_FOUND:
    $response->redirect('/404');
    break;
  case Dispatcher::METHOD_NOT_ALLOWED:
    $response->setContent('405 - Method not allowed');
    $response->setStatusCode(405);
    break;
  case Dispatcher::FOUND:
    $className = $routeInfo[1][0];
    $method = $routeInfo[1][1];
    $vars = $routeInfo[2];
    
    $class = $injector->make($className);
    $class->$method($vars);
    break;
}

foreach ($response->getHeaders() as $header) {
  header($header, false);
}

echo $response->getContent();

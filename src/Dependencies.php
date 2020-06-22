<?php declare(strict_types = 1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

$injector = new \Auryn\Injector;

$injector->define('Http\HttpRequest', [
  ':get' => $_GET,
  ':post' => $_POST,
  ':cookies' => $_COOKIE,
  ':files' => $_FILES,
  ':server' => $_SERVER,
]);

$injector->delegate('App\Helper\Router', function () use ($injector) {
  $router = require_once  __DIR__ . '/Routes/index.php';
  $router->get('/404', 'App\Controllers\Defaultpage@show404');
  return $router;
});
$injector->share('App\Helper\Router');

$injector->alias('Http\Request', 'Http\HttpRequest');
$injector->share('Http\HttpRequest');

$injector->alias('Http\Response', 'Http\HttpResponse');
$injector->share('Http\HttpResponse');


$injector->delegate('\Twig\Environment', function () use ($injector) {
  $loader = new Twig_Loader_Filesystem(dirname(__DIR__) . '/templates');
  $twig = new Twig_Environment($loader);
  return $twig;
});

$injector
  ->delegate('\Doctrine\ORM\EntityManager', function () use ($injector) {
      $configuration = Setup::createAnnotationMetadataConfiguration(
        [__DIR__ . '/Entities'], true, null, null, false);
      $connection_parameters = [
        'dbname' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASS'],
        'host' => $_ENV['DB_HOST'],
        'driver' => $_ENV['DB_DRIVER'],
        'driverOptions'	=> ['1002'=> "SET NAMES 'UTF8' COLLATE 'utf8_general_ci'"]
      ];
      return EntityManager::create($connection_parameters, $configuration);
    })
    ->share('\Doctrine\ORM\EntityManager');

$injector->alias('App\Template\IAdminRenderer', 'App\Template\AdminTwigRenderer');
$injector->alias('App\Template\IRenderer', 'App\Template\TwigRenderer');

$injector->define('Mustache_Engine', [
  ':options' => [
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__DIR__) . '/templates', [
      'extension' => '.html',
    ]),
  ],
]);

return $injector;

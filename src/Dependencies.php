<?php declare(strict_types = 1);

$injector = new \Auryn\Injector;

$injector->alias('Http\Request', 'Http\HttpRequest');
$injector->share('Http\HttpRequest');
$injector->define('Http\HttpRequest', [
  ':get' => $_GET,
  ':post' => $_POST,
  ':cookies' => $_COOKIE,
  ':files' => $_FILES,
  ':server' => $_SERVER,
  ]);
  
$injector->alias('Http\Response', 'Http\HttpResponse');
$injector->share('Http\HttpResponse');


$injector->delegate('\Twig\Environment', function () use ($injector) {
  $loader = new Twig_Loader_Filesystem(dirname(__DIR__) . '/templates');
  $twig = new Twig_Environment($loader);
  return $twig;
});

$injector->delegate('\Doctrine\ORM\EntityManager', function () use ($injector) {
  $configuration = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration([__DIR__ . '/Entities'], true, null, null, false);
  $connection_parameters = include('Config/connection.php');
  $em = \Doctrine\ORM\EntityManager::create($connection_parameters['mysql'], $configuration);
  return $em;
});

$injector->share('\Doctrine\ORM\EntityManager');

$injector->alias('App\Template\IRenderer', 'App\Template\TwigRenderer');
$injector->alias('App\Template\IAdminRenderer', 'App\Template\AdminTwigRenderer');

$injector->define('Mustache_Engine', [
  ':options' => [
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__DIR__) . '/templates', [
        'extension' => '.html',
    ]),
  ],
]);
  
return $injector;

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

$injector->alias('App\Template\Renderer', 'App\Template\TwigRenderer');
$injector->alias('App\Template\FrontendRenderer', 'App\Template\FrontendTwigRenderer');

$injector->define('Mustache_Engine', [
  ':options' => [
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__DIR__) . '/templates', [
        'extension' => '.html',
    ]),
  ],
]);

$injector->define('App\Page\FilePageReader', [
  ':pageFolder' => __DIR__ . '/../pages',
]);

$injector->alias('App\Page\PageReader', 'App\Page\FilePageReader');
$injector->share('App\Page\FilePageReader');
  
return $injector;

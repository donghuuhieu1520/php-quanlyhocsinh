<?php declare(strict_types = 1);

namespace App\Template;

use \Twig\Environment;
use App\Helper\Router;

class TwigRenderer implements IRenderer
{
  private $renderer;
  private $routes;

  public function __construct(Environment $renderer, Router $router)
  {
    $this->renderer = $renderer;
    $this->routes = $router->getNames();
  }

  public function render($template, $data = []) : string
  {
    $data = array_merge($data, [
      'routes' => $this->routes
    ]);

    $loadTemplate = $this->renderer->load("$template.html");
    return $loadTemplate->render($data);
  }
}

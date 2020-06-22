<?php declare(strict_types = 1);

namespace App\Template;

use Twig\Environment;
use App\Helper\Router;

class AdminTwigRenderer implements IAdminRenderer
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
      'manage_classes' => array_map(function ($class) {
        return [
          'id' => $class['id'],
          'name' => $class['name']
        ];
      }, $_SESSION['manage_classes']),
      'account' => [
        'id' => $_SESSION['account_login']['id'],
        'name' => $_SESSION['account_login']['name'],
      ],
      'routes' => $this->routes
    ]);
    return $this->renderer->render("$template.html", $data);
  }
}

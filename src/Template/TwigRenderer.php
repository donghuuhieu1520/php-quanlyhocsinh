<?php declare(strict_types = 1);

namespace App\Template;

use \Twig\Environment;

class TwigRenderer implements Renderer
{
  private $renderer;

  public function __construct(Environment $renderer)
  {
    $this->renderer = $renderer;
  }

  public function render($template, $data = []) : string
  {
    $loadTemplate = $this->renderer->load("$template.html");
    return $loadTemplate->render($data);
  }
}


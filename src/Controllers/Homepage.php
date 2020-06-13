<?php declare(strict_types = 1);

namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Template\IRenderer;

class Homepage
{
    private $request;
    private $response;
    private $renderer;
  
    public function __construct(Request $request, Response $response, IRenderer $renderer)
    {
      $this->request = $request;
      $this->response = $response;
      $this->renderer = $renderer;
    }

    public function show()
    {
      $html = $this->renderer->render("Homepage");
      $this->response->setContent($html);
    }
}

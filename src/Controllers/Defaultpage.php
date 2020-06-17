<?php


namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Template\IRenderer;

class Defaultpage
{
  /**
   * @var Request
   */
  protected Request $request;

  /**
   * @var Response
   */
  protected Response $response;

  /**
   * @var IRenderer
   */
  protected IRenderer $renderer;

  public function __construct(Request $request, Response $response, IRenderer $renderer)
  {
    $this->request = $request;
    $this->response = $response;
    $this->renderer = $renderer;
  }

  public function show404()
  {
    $html = $this->renderer->render('404');
    $this->response->setContent($html);
    $this->response->setStatusCode(404);
  }
}
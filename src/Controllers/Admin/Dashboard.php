<?php

namespace App\Controllers\Admin;

use Http\Request;
use Http\Response;
use App\Template\IAdminRenderer;
use Doctrine\ORM\EntityManager;

class Dashboard
{
  private $request;
  private $response;
  private $renderer;
  
  public function __construct(Request $request, Response $response, IAdminRenderer $renderer)
  {
    $this->request = $request;
    $this->response = $response;
    $this->renderer = $renderer;
  }

  private function hasLogedIn() : bool
  {
    return isset($_SESSION['account_id']);
  }
  
  public function show()
  {
    $this->hasLogedIn();
  }
}

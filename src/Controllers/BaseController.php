<?php

namespace App\Controllers;

use Http\Request;
use Http\Response;
use Doctrine\ORM\EntityManager;

class BaseController
{
  protected $request;
  protected $response;
  protected $em;

  protected function __construct(Request $req, Response $res, EntityManager $em)
  {
    $this->request = $req;
    $this->response = $res;
    $this->em = $em;
  }

  protected function isLoggedIn() : bool
  {
    return isset($_SESSION['account_id']);
  }
}


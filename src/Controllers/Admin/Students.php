<?php

namespace App\Controllers\Admin;

use Http\Request;
use Http\Response;
use App\Template\IAdminRenderer;
use App\Controllers\BaseController;
use Doctrine\ORM\EntityManager;
use App\Helper\Now;

class Students extends BaseController
{
  private $renderer;

  public function __construct(Request $request, Response $response, IAdminRenderer $renderer, EntityManager $em)
  {
    parent::__construct($request, $response, $em);
    $this->renderer = $renderer;
  }

  /**
   * Show the student list page
   */
  public function showStudentList($param)
  {
    $classId = $param['classId'];
    if (!$this->isLoggedIn()) {
      $this->backToLogin();
      return;
    }

    $class =

    $html = $this->renderer->render('Dashboard', $data);
    $this->response->setContent($html);
  }
}

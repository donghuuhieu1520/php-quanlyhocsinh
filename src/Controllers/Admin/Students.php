<?php

namespace App\Controllers\Admin;

use Http\Request;
use Http\Response;
use App\Template\IAdminRenderer;
use Doctrine\ORM\EntityManager;
use App\Helper\Now;

class Students extends BaseAdminController
{

  public function __construct(Request $request, Response $response, EntityManager $em, IAdminRenderer $renderer)
  {
    parent::__construct($request, $response, $em, $renderer);
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

    $class = $this->getManagedClasses();

    $html = $this->renderer->render('Dashboard', $data);
    $this->response->setContent($html);
  }
}

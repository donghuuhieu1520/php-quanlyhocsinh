<?php

namespace App\Controllers\Admin;

use Http\Request;
use Http\Response;
use App\Template\IAdminRenderer;
use Doctrine\ORM\EntityManager;
use function _\some;

class Students extends BaseAdminController
{

  public function __construct(Request $request, Response $response, EntityManager $em, IAdminRenderer $renderer)
  {
    parent::__construct($request, $response, $em, $renderer);
  }

  /**
   * Show the student list page
   */
  public function show($param)
  {
    if (!$this->isLoggedIn()) {
      $this->backToLogin();
    }

    $classIds = array_map(function ($class) {
      return $class['id'];
    }, $this->getManagedClasses());

    if (some($classIds, function ($id) use ($param) { return $id === $param['classId']; })) {
     
    }

    $students = $this->em->getRepository('App\Entities\Students')->findBy([
       'class' =>
    ]);

    $this->response->setContent("hello");
  }
}

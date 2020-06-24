<?php

namespace App\Controllers\Admin;

use Http\Request;
use Http\Response;
use App\Template\IAdminRenderer;
use Doctrine\ORM\EntityManager;
use function _\some;
use App\Helper\Alfred;

class Students extends BaseAdminController
{

  public function __construct(Request $request, Response $response, EntityManager $em, IAdminRenderer $renderer)
  {
    parent::__construct($request, $response, $em, $renderer);
  }

  private function _retrieveStudent(int $requestClassId) {
    $classIds = array_map(function ($class) {
      return $class['id'];
    }, $this->getManagedClasses());

    // Check if class Id is belong to account's classes managaged
    if (!some($classIds, function ($id) use ($requestClassId) { return $id === $requestClassId; })) {
      return [];
    }

    $students = $this->em->getRepository('App\Entities\Students')->findBy([
      'class' => $requestClassId
    ]);

    return array_map(function ($student) {
        return $student->getRawData();
      }, $students);
  }

  public function getAll($param)
  {
    $requestClassId = (int)$param['classId'];
    $students = $this->_retrieveStudent($requestClassId);

    return Alfred::apiResponseWithSuccess($this->response, $students);
  }

  /**
   * Show the student list page
   */
  public function show($param)
  {
    if (!$this->isLoggedIn()) {
      return $this->backToLogin();
    }

    $requestClassId = (int)$param['classId'];

    $data = [
      'students' => $this->_retrieveStudent($requestClassId),
      'selectedClass' => $requestClassId
    ];

    $html = $this->renderer->render('showStudentByClass', $data);
    return $this->response->setContent($html);
  }
}

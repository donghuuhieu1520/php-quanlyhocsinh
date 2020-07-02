<?php

namespace App\Controllers\Admin;

use Doctrine\ORM\ORMException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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
    $hasAccess = $this->checkAccessOnClass($requestClassId);
    if (!$hasAccess) {
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

  public function showAdd()
  {
    if (!$this->isLoggedIn()) {
      return $this->backToLogin();
    }

    $html = $this->renderer->render('showAddStudentToClass', []);
    return $this->response->setContent($html);
  }

  public function add()
  {
    if (!$this->isLoggedIn()) {
      return Alfred::apiResponseNotLogin($this->response);
    }

    $accountACLs = $this->getAcl();

    if (!$accountACLs['canCreateStudent']) {
      return Alfred::apiResponseNotAllow($this->response);
    }

    $studentInfo = $this->request->getParameters();

    $hasAccess = $this->checkAccessOnClass($studentInfo['classId']);
    if (!$hasAccess) {
      return Alfred::apiResponseNotAllow($this->response);
    }

    $targetClass = $this->em->getRepository('App\Entities\Classes')->find($studentInfo['classId']);

    if ($targetClass == null) {
      return Alfred::apiResponseWithError($this->response, "Class is not found");
    }

    $newStudent = new \App\Entities\Students();
    $newStudent->setClass($targetClass);
    $newStudent->setFirstName($studentInfo['firstName']);
    $newStudent->setLastName($studentInfo['lastName']);
    $newStudent->setPhone($studentInfo['phone']);
    $newStudent->setGender($studentInfo['gender']);

    try {
      $this->em->persist($newStudent);
      $this->em->flush();
    } catch (ORMException $e) {
      return Alfred::apiResponseInternalError($this->response);
    }

    return Alfred::apiResponseWithSuccess($this->response, $newStudent->getRawData());
  }

  public function update($param)
  {
    if (!$this->isLoggedIn()) {
      return Alfred::apiResponseNotLogin($this->response);
    }

    $accountACLs = $this->getAcl();

    if (!$accountACLs['canUpdateStudent']) {
      return Alfred::apiResponseNotAllow($this->response);
    }

    $studentId = $param['studentId'];
    $studentInfo = $this->request->getParameters();

    $hasAccess = $this->checkAccessOnClass($studentInfo['classId']);
    if (!$hasAccess) {
      return Alfred::apiResponseNotAllow($this->response);
    }

    $targetClass = $this->em->getRepository('App\Entities\Classes')->find($studentInfo['classId']);

    if ($targetClass == null) {
      return Alfred::apiResponseWithError($this->response, "Class is not found");
    }

    $myStudent = $this->em->getRepository('App\Entities\Students')
                        ->findOneBy([ 'id' => $studentId ]);

    if ($myStudent == null) {
      return Alfred::apiResponseWithError($this->response, 'Student is not found');
    }

    $myStudent->setFirstName($studentInfo['firstName']);
    $myStudent->setLastName($studentInfo['lastName']);
    $myStudent->setPhone($studentInfo['phone']);
    $myStudent->setGender($studentInfo['gender'] == 1);
    $myStudent->setClass($targetClass);

    try {
      $this->em->flush();
    } catch (ORMException $e) {
      return Alfred::apiResponseInternalError($this->response);
    }

    return Alfred::apiResponseWithSuccess($this->response, $myStudent->getRawData());
  }

  public function delete ($param)
  {
    if (!$this->isLoggedIn()) {
      return Alfred::apiResponseNotLogin($this->response);
    }

    $accountACLs = $this->getAcl();

    if (!$accountACLs['canDeleteStudent']) {
      return Alfred::apiResponseNotAllow($this->response);
    }

    $studentId = (int)$param['studentId'];

    $student = $this->em->getRepository('App\Entities\Students')
        ->findOneBy([ 'id' => $studentId ]);

    if ($student == null) {
      return Alfred::apiResponseWithError($this->response, 'Student is not found');
    }

    $hasAccess = $this->checkAccessOnClass($student->getClass()->getId());

    if (!$hasAccess) {
      return Alfred::apiResponseNotAllow($this->response);
    }

    try {
      $this->em->remove($student);
      $this->em->flush();
    } catch (ORMException $e) {
      return Alfred::apiResponseWithError($this->response, 'Error has occurred. Please try later');
    }

    return Alfred::apiResponseWithSuccess($this->response, $studentId);
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

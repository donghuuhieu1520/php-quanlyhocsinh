<?php

namespace App\Controllers\Admin;

use Cassandra\Date;
use Doctrine\ORM\ORMException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Http\Request;
use Http\Response;
use App\Template\IAdminRenderer;
use Doctrine\ORM\EntityManager;
use Moment\Moment;
use function _\find;
use function _\findIndex;
use function _\reduce;
use function _\reduceRight;
use function _\some;
use App\Helper\Alfred;
use function Doctrine\ORM\QueryBuilder;

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

  public function showProfile($param)
  {
    if (!$this->isLoggedIn()) {
      return $this->backToLogin();
    }
    $studentId = (int)$param['studentId'];
    $viewDate = $this->request->getParameter('viewDate', new \DateTime('now'));

    if (is_string($viewDate)) {
      $viewDate = \DateTime::createFromFormat('d-m-Y', $viewDate);
    }

    $student = $this->em->getRepository('App\Entities\Students')
      ->findOneBy([ 'id' => $studentId, 'class' => $this->getManagedClassIds() ]);

    if ($student == null) {
      return $this->backTo404();
    }

    $now = new Moment($viewDate->format(\DateTime::ATOM));
    $currentMonth = $viewDate->format('m');

    $payload = [
        'info' => $student->getRawData(),
        'studentToRules' => array_map(function ($s2r) {
          return $s2r->getRawData();
        }, array_filter($student->getStudentsToRules()->toArray(), function ($s2r) use ($currentMonth) {
          return $s2r->getCreatedAt()->format('m') == $currentMonth;
        })),
        'date' => [
            'weekStart' => $now->getPeriod('week')->getStartDate()->format('d-m-Y'),
            'weekEnd' => $now->getPeriod('week')->getEndDate()->format('d-m-Y'),
            'monthStart' => $now->getPeriod('month')->getStartDate()->format('d-m-Y'),
            'monthEnd' => $now->getPeriod('month')->getEndDate()->format('d-m-Y'),
            'today' => $now->format('d-m-Y'),
            'month' => $now->getMonth()
        ]
    ];

    $payload['studenToRulesGroupedByDay'] = reduceRight($payload['studentToRules'], function ($carry, $item) {
      $day = Moment::fromDateTime($item['createdAt'])->format('d-m-Y');

      $found = findIndex($carry, function ($exist) use ($day) {
        return $exist['label'] == $day;
      });

      if ($found != -1) {
        $carry[$found]['y'] += 1;
      } else {
        $carry[] = ['label' => $day, 'y' => 1];
      }

      return $carry;
    }, []);

    $html = $this->renderer->render('showStudentProfile', $payload);
    return $this->response->setContent($html);
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

  public function search()
  {
    if (!$this->isLoggedIn()) {
      return Alfred::apiResponseNotLogin($this->response);
    }

    $searchData = $this->request->getParameters();
    $managedClassIds = $this->getManagedClassIds();

    $qb = $this->em->createQueryBuilder();
    $q = $qb->select('s')
      ->from('\App\Entities\Students', 's')
      ->where('s.first_name LIKE :name')
      ->orWhere('s.last_name LIKE :name')
      ->andWhere($qb->expr()->in('s.class', $managedClassIds))
      ->setParameter('name', '%' . $searchData['studentName'] . '%')
      ->getQuery();

    $students = $q->getResult();

    $payload = array_map(function ($student) {
      return $student->getRawData();
    }, $students);

    return Alfred::apiResponseWithSuccess($this->response, $payload);
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

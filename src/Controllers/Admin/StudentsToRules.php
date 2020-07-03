<?php

namespace App\Controllers\Admin;
use Doctrine\ORM\ORMException;
use Http\Request;
use Http\Response;
use App\Template\IAdminRenderer;
use Doctrine\ORM\EntityManager;
use Moment\Moment;
use Moment\MomentException;
use function _\some;
use App\Helper\Alfred;
use function Doctrine\ORM\QueryBuilder;

class StudentsToRules extends BaseAdminController
{
  public function __construct(Request $request, Response $response, EntityManager $em, IAdminRenderer $renderer)
  {
    parent::__construct($request, $response, $em, $renderer);
  }

  public function add()
  {
    // if (!$this->isLoggedIn()) {
    //   return Alfred::apiResponseNotLogin($this->response);
    // }

    $data = $this->request->getParameters();

    $studentId = $data['studentId'];

    $ruleId = $data['ruleId'];

    $date = $data['date'];

    $accountId = 1;

    $student = $this->em->getRepository('App\Entities\Students')
                        ->findOneBy([ 'id' => $studentId ]);
    $rule = $this->em->getRepository('App\Entities\Rules')
                        ->findOneBy([ 'id' => $ruleId ]);
    $account = $this->em->getRepository('App\Entities\Accounts')
                        ->findOneBy([ 'id' => $accountId ]);

    $newStr = new \App\Entities\StudentsToRules();

    $newStr->setCreateAt($date);
    $newStr->setStudent($student);
    $newStr->setRule($rule);
    $newStr->setAccount($account);

    try {
      $this->em->persist($newStr);
      $this->em->flush();
    } catch (ORMException $e) {
      return Alfred::apiResponseInternalError($this->response);
    }
    return Alfred::apiResponseWithSuccess($this->response, []);
  }

  public function showSearchPage()
  {
    $html = $this->renderer->render('showSearchStudentToRulesPage', []);
    return $this->response->setContent($html);
  }

  public function search()
  {
    if (!$this->isLoggedIn()) {
      return Alfred::apiResponseNotLogin($this->response);
    }

    $param = $this->request->getParameters();

    $studentIds = [];

    if ($param['searchBy'] == 'student') {
      $studentIds = $this->_searchByStudentName($param['studentName']);
    } else {
      $studentIds = $this->_searchByClassId($param['classId']);
    }

    if (count($studentIds) == 0) {
      return Alfred::apiResponseWithSuccess($this->response, []);
    }

    $qb = $this->em->createQueryBuilder();
    $qb = $qb->select('s2r')
        ->from('\App\Entities\StudentsToRules', 's2r')
        ->where($qb->expr()->in('s2r.student', $studentIds));

    if (array_key_exists('from', $param) && $param['from']) {
      try {
        $from = new Moment($param['from']);
        $from = $from->format('Y-m-d');
        $qb = $qb->andWhere($qb->expr()->gte('s2r.created_at', ':from'));
        $qb = $qb->setParameter('from', "{$from} 00:00:00");
      } catch (MomentException $e) {

      }

    }

    if (array_key_exists('to', $param) && $param['to']) {
      try {
        $to = new Moment($param['to']);
        $to = $to->format('Y-m-d');
        $qb = $qb->andWhere($qb->expr()->lte('s2r.created_at', ':to'));
        $qb = $qb->setParameter('to', "{$to} 23:59:59");
      } catch (MomentException $e) {

      }
    }

    $query = $qb->getQuery();

    $s2rs = $query->execute();

    $payload = array_map(function ($s2r) {
      return $s2r->getRawData();
    }, $s2rs);

    return Alfred::apiResponseWithSuccess($this->response, $payload);
  }

  private function _searchByStudentName($studentName)
  {
    $qb = $this->em->createQueryBuilder();
    $q = $qb->select('s')
        ->from('App\Entities\Students', 's')
        ->where($qb->expr()->like('s.first_name', ':name'))
        ->orWhere($qb->expr()->like('s.last_name', ':name'))
        ->andWhere($qb->expr()->in('s.class', ':classManagedIds'))
        ->setParameter('name', "%{$studentName}%")
        ->setParameter('classManagedIds', $this->getManagedClassIds())
        ->getQuery();

    return array_map(function ($student) {
      return $student->getId();
    }, $q->getResult());
  }

  private function _searchByClassId($classId)
  {
    $qb = $this->em->createQueryBuilder();
    $q = $qb->select('s')
        ->from('App\Entities\Students', 's')
        ->where('s.class = :classId')
        ->setParameter('classId', $classId)
        ->getQuery();

    return array_map(function ($student) {
      return $student->getId();
    }, $q->getResult());
  }

  public function getStudentToRule($param) {

    $filter = isset($param['studentId']) ? ['student' => (int) $param['studentId']] : [];

    $rules = $this->em->getRepository('App\Entities\StudentsToRules')->findBy($filter);

    $data = array_map(function ($rule) {
      return $rule->getRawData();
    }, $rules);

    return Alfred::apiResponseWithSuccess($this->response, $data);
  }

  public function showAddStudentToRule()
  {
    if (!$this->isLoggedIn()) {
      return $this->backToLogin();
    }

    $rules = $this->em->getRepository('App\Entities\Rules')
      ->findAll();

    $rawRules = array_map(function ($rule) {
      return $rule->getRawData();
    }, $rules);

    $html = $this->renderer->render('showAddStudentToRule', [
        'rules' => $rawRules
    ]);
    return $this->response->setContent($html);
  }
}
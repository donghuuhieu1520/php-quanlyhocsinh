<?php


namespace App\Controllers\Admin;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Http\Request;
use Http\Response;
use App\Template\IAdminRenderer;
use Doctrine\ORM\EntityManager;
use function _\some;
use App\Helper\Alfred;
use function Doctrine\ORM\QueryBuilder;

class Rules extends BaseAdminController
{
  public function __construct(Request $request, Response $response, EntityManager $em, IAdminRenderer $renderer)
  {
    parent::__construct($request, $response, $em, $renderer);
  }

  public function showSearchPage()
  {
    $html = $this->renderer->render('searchRulesPage', []);
    return $this->response->setContent($html);
  }

  public function search()
  {
    if (!$this->isLoggedIn()) {
      $this->backToLogin();
      return;
    }

    $param = $this->request->getParameters();

    $result = [
        'success' => false,
        'message' => 'Internal error'
    ];

    if ($param['searchBy'] == 'student') {
      $result = $this->_searchByStudentName($param['data']);
    } else {
      $result = $this->_searchByClassId($param['data']);
    }

    if ($result['success']) {
      return Alfred::apiResponseWithSuccess($this->response, $result['payload']);
    }

    return Alfred::apiResponseWithError($this->response, $result['message']);
  }

  private function _searchByStudentName($data)
  {
    $qb = $this->em->createQueryBuilder();
    $q = $qb->select('s')
        ->from('App\Entities\Students', 's')
        ->where($qb->expr()->like('s.first_name', ':name'))
        ->orWhere($qb->expr()->like('s.last_name', ':name'))
        ->andWhere($qb->expr()->in('s.class', ':classManagedIds'))
        ->setParameter('name', "%{$data}%")
        ->setParameter('classManagedIds', $this->getManagedClassIds())
        ->getQuery();

    $studentsToRules = array_map(function ($student) {
    }, $q->getResult());

    exit();

    return [
        'success' => true,
        'payload' => array_map(function ($student) {
          return $student->getRawData();
        }, $students)
    ];
  }

  private function _searchByClassId($data)
  {
    return [
        'success' => false,
        'message' => 'Search by sclas'
    ];
  }
}
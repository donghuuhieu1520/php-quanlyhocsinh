<?php

namespace App\Controllers\Admin;

use App\Helper\Alfred;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr\Join;
use Http\Request;
use Http\Response;
use App\Template\IAdminRenderer;
use Doctrine\ORM\EntityManager;
use App\Helper\Now;
use Moment\Moment;
use function Doctrine\ORM\QueryBuilder;

class Dashboard extends BaseAdminController
{
  public function __construct(Request $request, Response $response, EntityManager $em,  IAdminRenderer $renderer)
  {
    parent::__construct($request, $response, $em, $renderer);
  }

  public function getDetail()
  {
    if (!$this->isLoggedIn()) {
      return Alfred::apiResponseNotLogin($this->response);
    }

    $type = $this->request->getParameter('type');

    $payload = [];

    switch ($type) {
      case '0': { // Get all S2R today
        $m = new Moment();
        $startOfDay = $m->startOf('day')->format('Y-m-d H:i:s');
        $endOfDay = $m->endOf('day')->format('Y-m-d H:i:s');

        $payload = $this->getAllS2R($startOfDay, $endOfDay);
        break;
      }
      case '1': {
        $m = new Moment();
        $startOfWeek = $m->startOf('week')->format('Y-m-d H:i:s');
        $endOfWeek = $m->endOf('week')->format('Y-m-d H:i:s');

        $payload = $this->getAllS2R($startOfWeek, $endOfWeek);
        break;
      }
      case '2': {
        $m = new Moment();
        $startOfWeek = $m->startOf('week')->format('Y-m-d H:i:s');
        $endOfWeek = $m->endOf('week')->format('Y-m-d H:i:s');

        $payload = $this->getAllS2RByStudent($startOfWeek, $endOfWeek);
        break;
      }
      case '3': {
        $m = new Moment();
        $startOfWeek = $m->startOf('week')->format('Y-m-d H:i:s');
        $endOfWeek = $m->endOf('week')->format('Y-m-d H:i:s');

        $payload = $this->getAllS2RByClass($startOfWeek, $endOfWeek);
        break;
      }
    }

    return Alfred::apiResponseWithSuccess($this->response, $payload);
  }

  /**
   * Show the dashboard page
   */
  public function show()
  {
    if (!$this->isLoggedIn()) {
      $this->backToLogin();
      return;
    }

    Moment::setLocale('vi_VN');

    $m = new Moment();
    $startOfWeek = $m->startOf('week')->format('Y-m-d H:i:s');
    $endOfWeek = $m->endOf('week')->format('Y-m-d H:i:s');
    $toDay = (new \DateTime())->format('Y-m-d');
    $now = new Now();

    $s2rOfTheWeek = $this->getAllS2R($startOfWeek, $endOfWeek);
    $s2rOfTheWeekByStudent = $this->getAllS2RByStudent($startOfWeek, $endOfWeek);
    $s2rOfTheWeekByClass = $this->getAllS2RByClass($startOfWeek, $endOfWeek);

    $s2rOfToday = array_filter($s2rOfTheWeek, function ($s2r) use ($toDay) {
      return $s2r['createdAt']->format('Y-m-d') == $toDay;
    });
    $chartMap = array_reduce($s2rOfTheWeek, function ($carry, $item) {
       $weekDay = Moment::fromDateTime($item['createdAt'])->format('l');
       if (array_key_exists($weekDay, $carry)) {
         $carry[$weekDay] += 1;
       } else {
         $carry[$weekDay] = 1;
       }
       return $carry;
    }, []);

    $chartData = [];

    foreach ($chartMap as $key => $value) {
      $chartData[] = [
          'label' => $key,
          'y' => $value
      ];
    }

    $data = [
        'date' => [
            'weekStart' => $now->weekStart(),
            'weekEnd' => $now->weekEnd(),
            'today' => $now->day(),
            'month' => $now->month()
        ],
        'countS2rToday' => count($s2rOfToday),
        'countS2rWeek' => count($s2rOfTheWeek),
        'countS2rWeekByStudent' => count($s2rOfTheWeekByStudent),
        's2rOfTheWeekByClass' => count($s2rOfTheWeekByClass),
        'chartData' => $chartData
    ];

    $html = $this->renderer->render('Dashboard', $data);
    $this->response->setContent($html);
  }

  private function getAllS2R($from, $to) {
    $qb = $this->em->createQueryBuilder();
    $q = $qb->select('s2r')
        ->from('App\Entities\StudentsToRules', 's2r')
        ->join('s2r.student', 's')
        ->join('s.class', 'c')
        ->where($qb->expr()->in('c.id', $this->getManagedClassIds()))
        ->andWhere($qb->expr()->between('s2r.created_at', ":from", ":to"))
        ->setParameter('from', new \DateTime($from))
        ->setParameter('to', new \DateTime($to))
        ->addOrderBy('s2r.created_at')
        ->getQuery();

    $results = $q->getResult();

    if ($results == null) return [];

    return array_map(function ($s2r) {
      return $s2r->getRawData();
    }, $results);
  }


  private function getAllS2RByStudent($from, $to)
  {
    $qb = $this->em->createQueryBuilder();
    $q = $qb->select(['s.id as studentId', 'c.id as classId', 'c.name as className', 'COUNT(s2r.student) AS quantity'])
        ->addSelect( "CONCAT( CONCAT(s.first_name, ' '),  s.last_name) as studentName" )
        ->from('App\Entities\StudentsToRules', 's2r')
        ->join('s2r.student', 's')
        ->join('s.class', 'c')
        ->where($qb->expr()->in('c.id', $this->getManagedClassIds()))
        ->andWhere($qb->expr()->between('s2r.created_at', ":from", ":to"))
        ->setParameter('from', $from)
        ->setParameter('to', $to)
        ->groupBy('s2r.student')
        ->getQuery();

    return $q->getResult();
  }

  private function getAllS2RByClass($from, $to)
  {
    $qb = $this->em->createQueryBuilder();
    $q = $qb->select(['c.id as classId', 'c.name as className', 'COUNT(s2r.student) AS quantity'])
        ->from('App\Entities\StudentsToRules', 's2r')
        ->join('s2r.student', 's')
        ->join('s.class', 'c')
        ->where($qb->expr()->in('c.id', $this->getManagedClassIds()))
        ->andWhere($qb->expr()->between('s2r.created_at', ":from", ":to"))
        ->setParameter('from', $from)
        ->setParameter('to', $to)
        ->groupBy('c.id')
        ->getQuery();

    return $q->getResult();
  }

  /**
   * Do logout
   */
  public function logout()
  {
    session_unset();
    $this->backToLogin();
  }
}

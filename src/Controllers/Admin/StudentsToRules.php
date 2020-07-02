<?php

namespace App\Controllers\Admin;
use Doctrine\ORM\ORMException;
use Http\Request;
use Http\Response;
use App\Template\IAdminRenderer;
use Doctrine\ORM\EntityManager;
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

    $accountId = 1;

    $student = $this->em->getRepository('App\Entities\Students')
                        ->findOneBy([ 'id' => $studentId ]);
    $rule = $this->em->getRepository('App\Entities\Rules')
                        ->findOneBy([ 'id' => $ruleId ]);
    $account = $this->em->getRepository('App\Entities\Accounts')
                        ->findOneBy([ 'id' => $accountId ]);

    $newStr = new \App\Entities\StudentsToRules();

    $newStr->setCreateAt();
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
}
<?php


namespace App\Controllers\Admin;

use Doctrine\ORM\Query\Expr\Join;

use Doctrine\ORM\ORMException;
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

  public function getAll()
  {
    if (!$this->isLoggedIn()) {
      return Alfred::apiResponseNotLogin($this->response);
    }

    $accountACLs = $this->getAcl();

    if (!$accountACLs['canReadRule']) {
      return Alfred::apiResponseNotAllow($this->response);
    }

    $ruleType = $this->request->getParameter('ruleType', '2');
    $condition = [];

    if ($ruleType == '1') {
      $condition = ['is_bad' => true];
    }

    if ($ruleType == '0') {
      $condition = ['is_bad' => false];
    }

    $rules = $this->em->getRepository('App\Entities\Rules')->findBy($condition);

    $payload = [];
    foreach ($rules as $rule) {
      $payload[] = $rule->getRawData();
    }
    return Alfred::apiResponseWithSuccess($this->response, $payload); // No need to return payload :v
  }

  public function delete($params)
  {
    if (!$this->isLoggedIn()) {
      return Alfred::apiResponseNotLogin($this->response);
    }

    $accountACLs = $this->getAcl();

    if (!$accountACLs['canDeleteRule']) {
      return Alfred::apiResponseNotAllow($this->response);
    }

    $rule = $this->em->getRepository('\App\Entities\Rules')
        ->findOneBy([ 'id' => $params['ruleId'] ]);

    if ($rule == null) {
      return Alfred::apiResponseWithError($this->response, 'Khong tim thay rule');
    }

    try {
      $this->em->remove($rule);
      $this->em->flush();
    } catch (ORMException $e) {
      return Alfred::apiResponseWithError($this->response, 'Error has occurred. Please try later');
    }

    return Alfred::apiResponseWithSuccess($this->response, []);

  }

  public function add()
  {
    if (!$this->isLoggedIn()) {
      return Alfred::apiResponseNotLogin($this->response);
    }

    $accountACLs = $this->getAcl();

    if (!$accountACLs['canCreateRule']) {
      return Alfred::apiResponseNotAllow($this->response);
    }

    $data = $this->request->getParameters();

    $newRule = new \App\Entities\Rules();
    $newRule->setName($data['ten_add']);
    $newRule->setIsBad($data['loai_add'] == '1');

    try {
      $this->em->persist($newRule);
      $this->em->flush();
    } catch (ORMException $e) {
      return Alfred::apiResponseInternalError($this->response);
    }

    return Alfred::apiResponseWithSuccess($this->response, []); // No need to return payload :v
  }

  public function update()
  {
    if (!$this->isLoggedIn()) {
      return Alfred::apiResponseNotLogin($this->response);
    }

    $accountACLs = $this->getAcl();

    if (!$accountACLs['canUpdateRule']) {
      return Alfred::apiResponseNotAllow($this->response);
    }

    $data = $this->request->getParameters();

    $rule = $this->em->getRepository('\App\Entities\Rules')
      ->findOneBy([ 'id' => $data['ruleId'] ]);

    if ($rule == null) {
      return Alfred::apiResponseWithError($this->response, 'Khong tim thay rule');
    }

    $rule->setName($data['ruleName']);
    $rule->setIsBad($data['ruleIsBad']);

    try {
      $this->em->flush();
    } catch (ORMException $e) {
      return Alfred::apiResponseInternalError($this->response);
    }

    return Alfred::apiResponseWithSuccess($this->response, []);
  }

  public function showManagePage()
  {
    $html = $this->renderer->render('showManageRulesPage', []);
    return $this->response->setContent($html);
  }
}

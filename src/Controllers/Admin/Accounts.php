<?php


namespace App\Controllers\Admin;

use App\Entities\ACLs;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Http\Request;
use Http\Response;
use App\Template\IAdminRenderer;
use Doctrine\ORM\EntityManager;
use function _\some;
use App\Helper\Alfred;
use function Doctrine\ORM\QueryBuilder;


class Accounts extends BaseAdminController
{
  protected static $acls = [
      ['name' => 'canReadRule', 'text' => 'Xem danh sach ne nep', 'default' => true],
      ['name' => 'canCreateRule', 'text' => 'Tao danh sach ne nep', 'default' => false],
      ['name' => 'canUpdateRule', 'text' => 'Cap nhat danh sach ne nep', 'default' => false],
      ['name' => 'canDeleteRule', 'text' => 'Xoa danh sach ne nep', 'default' => false],
      ['name' => 'canReadStudent', 'text' => 'Xem danh sach hoc sinh', 'default' => true],
      ['name' => 'canCreateStudent', 'text' => 'Tao hoc sinh moi', 'default' => false],
      ['name' => 'canUpdateStudent', 'text' => 'Cap nhat thong tin hoc sinh', 'default' => false],
      ['name' => 'canDeleteStudent', 'text' => 'Xoa danh sach hoc sinh', 'default' => false],
      ['name' => 'canReadAccount', 'text' => 'Xem danh sach tai khoan', 'default' => false],
      ['name' => 'canCreateAccount', 'text' => 'Them moi tai khoan', 'default' => false],
      ['name' => 'canUpdateAccount', 'text' => 'Cap nhat thong tin tai khoan', 'default' => false],
      ['name' => 'canDeleteAccount', 'text' => 'Xoa tai khoan', 'default' => false],
  ];

  public function __construct(Request $req, Response $res, EntityManager $em, IAdminRenderer $renderer)
  {
    parent::__construct($req, $res, $em, $renderer);
  }

  public function showManagePage()
  {
    $html = $this->renderer->render('showManageAccountsPage', ['acls' => self::$acls]);
    return $this->response->setContent($html);
  }

  public function getAll()
  {
    if (!$this->isLoggedIn()) {
      return Alfred::apiResponseNotLogin($this->response);
    }

    $accountACLs = $this->getAcl();

    if (!$accountACLs['canReadAccount']) {
      return Alfred::apiResponseNotAllow($this->response);
    }

    $qb = $this->em->createQueryBuilder();
    $qb->select('a')
      ->from('\App\Entities\Accounts', 'a')
      ->where('a.id != :accountId')
      ->setParameter('accountId', $this->getAccountId());
    $q = $qb->getQuery();
    $accounts = $q->getResult();

    return Alfred::apiResponseWithSuccess($this->response, array_map(function ($account) {
      return $account->getRawData();
    }, $accounts));
  }

  public function delete($param)
  {
    if (!$this->isLoggedIn()) {
      return Alfred::apiResponseNotLogin($this->response);
    }

    $accountACLs = $this->getAcl();

    if (!$accountACLs['canDeleteAccount']) {
      return Alfred::apiResponseNotAllow($this->response);
    }

    $deletedAccountId = (int)$param['accountId'];

    $account = $this->em->getRepository('\App\Entities\Accounts')->findOneBy([ 'id' => $deletedAccountId ]);
    if ($account == null) {
      return Alfred::apiResponseWithError($this->response, 'Khong tim thay tai khoan');
    }

    try {
      $this->em->remove($account);
      $this->em->flush();
    } catch (ORMException $e) {
      return Alfred::apiResponseInternalError($this->response);
    }

    return Alfred::apiResponseWithSuccess($this->response, ['username' => $account->getUsername()]);
  }

  public function update($params)
  {
    if (!$this->isLoggedIn()) {
      return Alfred::apiResponseNotLogin($this->response);
    }

    $accountACLs = $this->getAcl();

    if (!$accountACLs['canUpdateAccount']) {
      return Alfred::apiResponseNotAllow($this->response);
    }

    $updatedAccountId = (int)$params['accountId'];
    $data = $this->request->getParameters();

    $myAccount = $this->em->getRepository('\App\Entities\Accounts')->findOneBy([
        'id' => $updatedAccountId
    ]);

    if ($myAccount == null) {
      return Alfred::apiResponseWithError($this->response, 'Khong tim thay tai khoan');
    }

    $currentClassesToAccounts = $myAccount->getClassesToAccounts();
    array_map(function ($currentClassesToAccount) {
      $this->em->remove($currentClassesToAccount);
    }, $currentClassesToAccounts->toArray());

    try {
      $this->em->flush();
    } catch (OptimisticLockException $e) {
      return Alfred::apiResponseWithError($this->response, 'Da co loi xay ra');
    } catch (ORMException $e) {
      return Alfred::apiResponseWithError($this->response, 'Da co loi xay ra');
    }

    $myAccount->setUsername($data['username'])
        ->setEmail($data['email'])
        ->setName($data['name']);

    if ($data['password']) {
      $hashedPassword = Alfred::hashPassword($data['password'] || '123456');
      $myAccount->setPassword($hashedPassword);
    }

    $acls = $data['acls'];
    $accountACL = $myAccount->getAcl();
    $accountACL->setCanCreateAccount(in_array('canCreateAccount', $acls))
        ->setCanReadAccount(in_array('canReadAccount', $acls))
        ->setCanUpdateAccount(in_array('canUpdateAccount', $acls))
        ->setCanDeleteAccount(in_array('canDeleteAccount', $acls))
        ->setCanCreateRule(in_array('canCreateRule', $acls))
        ->setCanReadRule(in_array('canReadRule', $acls))
        ->setCanUpdateRule(in_array('canUpdateRule', $acls))
        ->setCanDeleteRule(in_array('canDeleteRule', $acls))
        ->setCanCreateStudent(in_array('canCreateStudent', $acls))
        ->setCanReadStudent(in_array('canReadStudent', $acls))
        ->setCanUpdateStudent(in_array('canUpdateStudent', $acls))
        ->setCanDeleteStudent(in_array('canDeleteStudent', $acls));

    $managedClasses = $this->em->getRepository('\App\Entities\Classes')->findBy([ 'id' => $data['managedClasses']]);
    $newClassesToAccounts = array_map(function ($class) use ($myAccount) {
      $newClassesToAccount = new \App\Entities\ClassesToAccounts();
      $newClassesToAccount->setClass($class)->setAccount($myAccount);
      return $newClassesToAccount;
    }, $managedClasses);

    $myAccount->setClassesToAccounts($newClassesToAccounts);

    try {
      array_map(function ($newClassesToAccount) {
        $this->em->persist($newClassesToAccount);
      }, $newClassesToAccounts);
      $this->em->flush();
    } catch (UniqueConstraintViolationException $e) {
      return Alfred::apiResponseWithError($this->response, 'Username da ton tai tren he thong');
    } catch (ORMException $e) {
      return Alfred::apiResponseInternalError($this->response);
    }
    return Alfred::apiResponseWithSuccess($this->response, $myAccount->getRawData());
  }

  public function add()
  {
    if (!$this->isLoggedIn()) {
      return Alfred::apiResponseNotLogin($this->response);
    }

    $accountACLs = $this->getAcl();

    if (!$accountACLs['canCreateAccount']) {
      return Alfred::apiResponseNotAllow($this->response);
    }


    $data = $this->request->getParameters();
    $acls = $data['acls'];
    $hashedPassword = Alfred::hashPassword($data['password'] || '123456');

    $managedClasses = $this->em->getRepository('\App\Entities\Classes')->findBy([ 'id' => $data['managedClasses']]);
    $newAccount = new \App\Entities\Accounts();
    $newAcl = new \App\Entities\ACLs();

    $newAcl->setCanCreateAccount(in_array('canCreateAccount', $acls))
        ->setCanReadAccount(in_array('canReadAccount', $acls))
        ->setCanUpdateAccount(in_array('canUpdateAccount', $acls))
        ->setCanDeleteAccount(in_array('canDeleteAccount', $acls))
        ->setCanCreateRule(in_array('canCreateRule', $acls))
        ->setCanReadRule(in_array('canReadRule', $acls))
        ->setCanUpdateRule(in_array('canUpdateRule', $acls))
        ->setCanDeleteRule(in_array('canDeleteRule', $acls))
        ->setCanCreateStudent(in_array('canCreateStudent', $acls))
        ->setCanReadStudent(in_array('canReadStudent', $acls))
        ->setCanUpdateStudent(in_array('canUpdateStudent', $acls))
        ->setCanDeleteStudent(in_array('canDeleteStudent', $acls));

    $newAccount->setUsername($data['username'])
      ->setEmail($data['email'])
      ->setPassword($hashedPassword)
      ->setName($data['name'])
      ->setAcl($newAcl);

    $newClassesToAccounts = array_map(function ($class) use ($newAccount) {
      $newClassesToAccount = new \App\Entities\ClassesToAccounts();
      $newClassesToAccount->setClass($class)->setAccount($newAccount);
      return $newClassesToAccount;
    }, $managedClasses);

    $newAccount->setClassesToAccounts($newClassesToAccounts);

    try {
      $this->em->persist($newAcl);
      $this->em->persist($newAccount);
      array_map(function ($newClassesToAccount) {
        $this->em->persist($newClassesToAccount);
      }, $newClassesToAccounts);
      $this->em->flush();
    } catch (UniqueConstraintViolationException $e) {
      return Alfred::apiResponseWithError($this->response, 'Username da ton tai tren he thong');
    } catch (ORMException $e) {
      return Alfred::apiResponseInternalError($this->response);
    }
    return Alfred::apiResponseWithSuccess($this->response, $newAccount->getRawData());
  }
}
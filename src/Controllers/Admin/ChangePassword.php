<?php


namespace App\Controllers\Admin;

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

class ChangePassword extends BaseAdminController
{
  public function __construct(Request $req, Response $res, EntityManager $em, IAdminRenderer $renderer)
  {
    parent::__construct($req, $res, $em, $renderer);
  }

  public function show()
  {
    $html = $this->renderer->render('changeAdminPasswordPage', []);
    return $this->response->setContent($html);
  }

  public function doChangePassword()
  {
    if (!$this->isLoggedIn()) {
      return Alfred::apiResponseNotLogin($this->response);
    }

    $data = $this->request->getParameters();
    $old_pass = $data['old_pass'];
    $new_pass = $data['new_pass'];

    $account = $this->em->getRepository('App\Entities\Accounts')
      ->findOneBy(['id' => $this->getAccountId()]);

    if ($account == null) {
      return Alfred::apiResponseWithError($this->response, 'Khong tim thay tai khoan');
    }

    $match = Alfred::verifyPassword($old_pass, $account->getPassword());
    if (!$match) {
      return Alfred::apiResponseWithError($this->response, 'Mat khau cu khong chinh xac');
    }

    $account->setPassword(Alfred::hashPassword($new_pass));
    try {
      $this->em->flush();
    } catch (OptimisticLockException $e) {
      return Alfred::apiResponseWithError($this->response, 'Da co loi xay ra, thu lai sau');
    } catch (ORMException $e) {
      return Alfred::apiResponseWithError($this->response, 'Da co loi xay ra, thu lai sau');
    }
    return Alfred::apiResponseWithSuccess($this->response, $account);
  }
}
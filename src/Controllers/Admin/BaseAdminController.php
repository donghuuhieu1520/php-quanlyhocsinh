<?php


namespace App\Controllers\Admin;


use App\Controllers\BaseController;
use App\Template\IAdminRenderer;
use Doctrine\ORM\EntityManager;
use Http\Request;
use Http\Response;

class BaseAdminController extends BaseController
{
  protected $renderer;

  protected function __construct(Request $req, Response $res, EntityManager $em, IAdminRenderer $renderer)
  {
    parent::__construct($req, $res, $em);
    $this->renderer = $renderer;
  }

  /**
   * Return an array of classes managed by account
   * @return array[]
   */
  protected function getManagedClasses()
  {
    return array_map(function ($class) {
      return [
        'id' => $class['id'],
        'name' => $class['name']
      ];
    }, $_SESSION['manage_classes']);
  }

  protected function getAcl()
  {
    return $_SESSION['account_login']['acl'];
  }

  protected function getManagedClassIds()
  {
    return array_map(function ($class) {
      return $class['id'];
    }, $_SESSION['manage_classes']);
  }

  protected function checkAccessOnClass($classId)
  {
    foreach ($_SESSION['manage_classes'] as $class) {
      if ($class['id'] == $classId) {
        return true;
      }
    }
    return false;
  }

  protected function getAccountId()
  {
    return $_SESSION['account_login']['id'];
  }
}
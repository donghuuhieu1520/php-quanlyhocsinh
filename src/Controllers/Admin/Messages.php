<?php


namespace App\Controllers\Admin;


use App\Template\IAdminRenderer;
use Doctrine\ORM\EntityManager;
use Http\Request;
use Http\Response;
use App\Helper\Alfred;

class Messages extends BaseAdminController
{
  public function __construct(Request $req, Response $res, EntityManager $em, IAdminRenderer $renderer)
  {
    parent::__construct($req, $res, $em, $renderer);
  }

  public function showManagePage()
  {
    $html = $this->renderer->render('showManageMessagesPage', []);
    $this->response->setContent($html);
  }

}
<?php


namespace App\Controllers\Admin;

use Doctrine\ORM\ORMException;
use Http\Request;
use Http\Response;
use App\Template\IAdminRenderer;
use Doctrine\ORM\EntityManager;
use function _\some;
use App\Helper\Alfred;

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
}
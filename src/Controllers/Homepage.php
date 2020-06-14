<?php declare(strict_types = 1);

namespace App\Controllers;

use Doctrine\ORM\EntityManager;
use Http\Request;
use Http\Response;
use App\Template\IRenderer;
use App\Controllers\BaseController;

class Homepage extends BaseController
{
    private $renderer;
  
    public function __construct(Request $request, Response $response, IRenderer $renderer, EntityManager $em)
    {
      parent::__construct($request, $response, $em);
      $this->renderer = $renderer;
    }

    public function show()
    {
      if ($this->isStudentLogin()) {
        $this->backToStudentPage();
        return;
      }

      if ($this->isLoggedIn()) {
        $this->backToAdminDashboard();
      }

      $html = $this->renderer->render("Homepage");
      $this->response->setContent($html);
    }
}

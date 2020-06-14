<?php

namespace App\Controllers\Admin;

use Http\Request;
use Http\Response;
use App\Template\IAdminRenderer;
use App\Controllers\BaseController;
use Doctrine\ORM\EntityManager;
use App\Helper\Now;

class Dashboard extends BaseController
{
  private $renderer;

  public function __construct(Request $request, Response $response, IAdminRenderer $renderer, EntityManager $em)
  {
    parent::__construct($request, $response, $em);
    $this->renderer = $renderer;
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

    $now = new Now();
    $data = [
        'date' => [
            'weekStart' => $now->weekStart(),
            'weekEnd' => $now->weekEnd(),
            'today' => $now->day(),
            'month' => $now->month()
        ],
    ];

    $html = $this->renderer->render('Dashboard', $data);
    $this->response->setContent($html);
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

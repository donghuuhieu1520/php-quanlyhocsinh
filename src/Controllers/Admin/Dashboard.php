<?php

namespace App\Controllers\Admin;

use Http\Request;
use Http\Response;
use App\Template\IAdminRenderer;
use Doctrine\ORM\EntityManager;
use App\Helper\Now;

class Dashboard extends BaseAdminController
{
  public function __construct(Request $request, Response $response, EntityManager $em,  IAdminRenderer $renderer)
  {
    parent::__construct($request, $response, $em, $renderer);
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

<?php

namespace App\Controllers;

use Http\Request;
use Http\Response;
use Doctrine\ORM\EntityManager;

class BaseController
{
  /**
   * @var Request
   */
  protected $request;

  /**
   * @var Response
   */
  protected $response;
  /**
   * @var EntityManager
   */
  protected $em;

  protected function __construct(Request $req, Response $res, EntityManager $em)
  {
    $this->request = $req;
    $this->response = $res;
    $this->em = $em;
  }

  /**
   * Check if whether this request has logged in as an admin
   * @return bool
   */
  protected function isLoggedIn() : bool
  {
    return isset($_SESSION['account_login']);
  }
  /**
   * Check if whether this request has logged in as a student
   * @return bool
   */
  protected function isStudentLogin() : bool
  {
    return isset($_SESSION['student_login']);
  }

  protected function backToLogin()
  {
    return $this->response->redirect('/login');
  }

  protected function backTo404()
  {
    return $this->response->redirect('/404');
  }

  protected function backToAdminDashboard()
  {
    return $this->response->redirect('/admin/dashboard');
  }

  protected function backToStudentPage()
  {
    return $this->response->redirect('/student');
  }
}


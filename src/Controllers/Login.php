<?php declare(strict_types = 1);

namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Template\IRenderer;
use App\Entities\Accounts;
use App\Entities\Roles;
use App\Entities\Classes;
use Doctrine\ORM\EntityManager;

class Login extends BaseController
{
    private $renderer;

    public function __construct(Request $request, Response $response, IRenderer $renderer, EntityManager $em)
    {
      parent::__construct($request, $response, $em);
      $this->renderer = $renderer;
    }

    public function show()
    {
      if ($this->isLoggedIn()) {
        $this->response->redirect('/dashboard/admin');
        return;
      }
      $html = $this->renderer->render("Login");
      $this->response->setContent($html);
    }

    public function doLogin()
    {
      $body = $this->request->getParameters();
      $account = $this->em->getRepository('App\Entities\Accounts')
                          ->findOneBy(['username' => $body['account']]);
      if ($account !== NULL) {
        if ($account->getPassword() == $body['password']) {
          $_SESSION['account_id'] = $account->getId();
          $accountRoles = $account->getRoles();
          $_SESSION['manage_classes'] = $accountRoles->map(function ($role) {
            return $role->getClass()->getId();
          });
          $this->response->setContent(json_encode([ 'success' => true ]));
          return;
        }
      }

      $this->response->setContent(json_encode([
        'success' => false,
        'message' => 'Account or password is invalid'
      ]));
    }
}

<?php declare(strict_types = 1);

namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Template\IRenderer;
use App\Entities\Accounts;
use App\Entities\Roles;
use App\Entities\Classes;
use Doctrine\ORM\EntityManager;
use App\Helper\Alfred;

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
        $this->backToAdminDashboard();
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
        if (Alfred::verifyPassword($body['password'], $account->getPassword())) {
          $accountAcl = $account->getAcl();
          $_SESSION['account_login'] = [
              'id' => $account->getId(),
              'username' => $account->getUsername(),
              'name' => $account->getName()
          ];

          if ($accountAcl != null) {
            $_SESSION['account_login']['acl'] = [
                'canReadRule' => $accountAcl->getCanReadRule(),
                'canCreateRule' => $accountAcl->getCanCreateRule(),
                'canUpdateRule' => $accountAcl->getCanUpdateRule(),
                'canDeleteRule' => $accountAcl->getCanDeleteRule(),
                'canReadStudent' => $accountAcl->getCanReadStudent(),
                'canCreateStudent' => $accountAcl->getCanCreateStudent(),
                'canUpdateStudent' => $accountAcl->getCanUpdateStudent(),
                'canDeleteStudent' => $accountAcl->getCanDeleteStudent()
            ];
          }

          $classesToAccount = $account->getClassesToAccounts();
          $_SESSION['manage_classes'] = $classesToAccount
              ->map(function ($classToAccount) {
                $class = $classToAccount->getClass();
                return [
                    'id' => $class->getId(),
                    'name' => $class->getName()
                ];
              })
              ->toArray();
          $this->response->setContent(json_encode([ 'success' => true ]));
          return;
        }
      }

      $this->response->setContent(json_encode([ 'success' => false, 'message' => 'Account or password is invalid' ]));
    }
}

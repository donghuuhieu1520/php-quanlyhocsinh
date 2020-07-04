<?php


namespace App\Controllers\Admin;


use App\Template\IAdminRenderer;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Exception\GuzzleException;
use Http\Request;
use Http\Response;
use App\Helper\Alfred;
use GuzzleHttp\Client;

class Messages extends BaseAdminController
{
  private $client;

  private $baseURI;
  private $apiKey;
  private $secretKey;
  private $costPerMessage;

  public function __construct(Request $req, Response $res, EntityManager $em, IAdminRenderer $renderer)
  {
    parent::__construct($req, $res, $em, $renderer);
    $this->baseURI = $_ENV['ESMS_ENDPOINT'];
    $this->apiKey = $_ENV['ESMS_API_KEY'];
    $this->secretKey = $_ENV['ESMS_SECRET_KEY'];
    $this->costPerMessage = 750;
    $this->client = new Client([ 'base_uri' => $this->baseURI ]);
  }

  public function getStudentRegistedSMS () {
    if (!$this->isLoggedIn()) {
      return Alfred::apiResponseNotLogin($this->response);
    }

    // TODO: check access
    $qb = $this->em->createQueryBuilder();
    $q = $qb->select('s')
        ->from('App\Entities\Students', 's')
        ->where('s.phone IS NOT NULL')
        ->getQuery();

    $students = $q->getResult();

    if ($students == null) {
      return Alfred::apiResponseWithSuccess($this->response, []);
    }

    $students = array_map(function ($student) {
        return $student->getRawData();
    }, $students);

    return Alfred::apiResponseWithSuccess($this->response, $students);
  }

  public function getBalanceAndAvailable()
  {
    if (!$this->isLoggedIn()) {
      return Alfred::apiResponseNotLogin($this->response);
    }
    // TODO: check access

    try {
      $response = $this->client->get("GetBalance/$this->apiKey/$this->secretKey");
    } catch (GuzzleException $e) {
      return Alfred::apiResponseInternalError($this->response);
    }

    $contents = (string) $response->getBody();
    $contents = \GuzzleHttp\json_decode($contents);

    return Alfred::apiResponseWithSuccess($this->response, [
        'balance' => $contents->Balance,
        'available' => Floor($contents->Balance / $this->costPerMessage)
    ]);
  }

  public function showManagePage()
  {
    if (!$this->isLoggedIn()) {
      return $this->backToLogin();
    }

    // TODO: check acl

    $html = $this->renderer->render('showManageMessagesPage', []);
    $this->response->setContent($html);
  }

}
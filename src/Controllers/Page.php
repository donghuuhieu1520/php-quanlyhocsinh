<?php declare(strict_types = 1);

namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Template\FrontendRenderer;
use App\Page\PageReader;
use App\Page\InvalidPageException;

use Exception;

class Page
{
  private $request;
  private $response;
  private $renderer;
  private $pageReader;

  public function __construct(Request $req, Response $res, FrontendRenderer $ren, PageReader $pageReader)
  {
    $this->request = $req;
    $this->response = $res;
    $this->renderer = $ren;
    $this->pageReader = $pageReader;
  }

  public function show($params)
  {
    $slug = $params['slug'];
    try {
      $data['content'] = $this->pageReader->readBySlug($slug);
    } catch (Exception $ex) {
      $this->response->setStatusCode(404);
      return $this->response->setContent('404');
    }

    $html = $this->renderer->render('Page', $data);
    return $this->response->setContent($html);
  }
}

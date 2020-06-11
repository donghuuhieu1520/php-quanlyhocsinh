<?php declare(strict_type = 1);

namespace App\Page;

use Exception;

class InvalidPageException extends Exception
{
  public function __construct($slug, $code = 0, Exception $previous = null)
  {
    $message = "No page with the slug `$slug` was found";
    parrent::__construct($message, $code, $previous);
  }
}

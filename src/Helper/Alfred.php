<?php


namespace App\Helper;


use Http\Response;

class Alfred
{
  public static function isValidIndentifier (string $str) : bool
  {
    if (strlen($str) == 0) return false;
    // If first character is invalid
    if (!(($str[0] >= 'a' && $str[0] <= 'z') ||
          ($str[0] >= 'A' && $str[1] <= 'Z') ||
           $str[0] == '_'))
        return false;
    // Traverse the string
    // for the rest of the characters
    for ($i = 1; $i < strlen($str); $i++) {
        if (!(($str[$i] >= 'a' && $str[$i] <= 'z') ||
              ($str[$i] >= 'A' && $str[$i] <= 'Z') ||
              ($str[$i] >= '0' && $str[$i] <= '9') ||
               $str[$i] == '_'))
            return false;
    }
    return true;
  }

  public static function apiResponseWithSuccess(Response $res, $data)
  {
    $data = [
        'success' => true,
        'payload' => $data
    ];

    $res->setHeader('Content-type', 'Application/json');
    return $res->setContent(json_encode($data));
  }
}

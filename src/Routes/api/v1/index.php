<?php

namespace App\Routes\api\v1;

use App\Helper\Router;

$apiRouter = new Router();

$apiRouter->useRoute('/admin', require_once __DIR__ . '/admin.php');

return $apiRouter;

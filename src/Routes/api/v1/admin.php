<?php

namespace App\Routes\api\v1;

use App\Helper\Router;

$apiAdminRouter = new Router();
$apiAdminRouter->get('/students/{classId}', 'App\Controllers\Admin\Student@getAll');

return $apiAdminRouter;

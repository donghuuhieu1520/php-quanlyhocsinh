<?php

namespace App\Routes\api\v1;

use App\Helper\Router;

return (new Router())
    ->get('/students/{classId}', 'App\Controllers\Admin\Student@getAll');

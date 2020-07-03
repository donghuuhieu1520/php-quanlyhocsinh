<?php

namespace App\Routes\api\v1;

use App\Helper\Router;

return (new Router())
    ->get('/detail', 'App\Controllers\Admin\Dashboard@getDetail', 'apiDashboardDetail');

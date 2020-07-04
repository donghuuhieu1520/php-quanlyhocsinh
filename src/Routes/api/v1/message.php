<?php

namespace App\Routes\api\v1;

use App\Helper\Router;

return (new Router())
    ->get('/balance', 'App\Controllers\Admin\Messages@getBalanceAndAvailable', 'apiGetMessageBalance')
    ->get('/studentRegisted', 'App\Controllers\Admin\Messages@getStudentRegistedSMS', 'apiGetStudentRegisted');

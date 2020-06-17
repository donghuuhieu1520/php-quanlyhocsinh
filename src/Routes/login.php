<?php


namespace App\Routes;

use App\Helper\Router;

$loginRouter = new Router();

$loginRouter->get('/', 'App\Controllers\Login@show')
            ->post('/', 'App\Controllers\Login@doLogin');

return $loginRouter;

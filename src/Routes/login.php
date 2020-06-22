<?php


namespace App\Routes;

use App\Helper\Router;

return (new Router())
    ->get('/', 'App\Controllers\Login@show')
    ->post('/', 'App\Controllers\Login@doLogin');

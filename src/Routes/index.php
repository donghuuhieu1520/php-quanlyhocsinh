<?php

namespace App\Routes;

use App\Helper\Router;

$router = new Router();

$router->get('/', 'App\Controllers\Homepage@show');
$router->get('/index.php', 'App\Controllers\Homepage@show');

$router->useRoute('/login', require_once __DIR__ . '/login.php');
$router->useRoute('/admin', require_once __DIR__ . '/admin.php');

return $router;

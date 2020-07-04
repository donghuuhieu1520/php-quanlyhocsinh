<?php

namespace App\Routes;

use App\Helper\Router;

return (new Router())
    ->get('/', 'App\Controllers\Login@show')
    ->get('/index.php', 'App\Controllers\Login@show')
    ->useRoute('/login', include(__DIR__ . '/login.php'))
    ->useRoute('/admin', include(__DIR__ . '/admin.php'))
    ->useRoute('/api/v1', include(__DIR__ . '/api/v1/index.php'));

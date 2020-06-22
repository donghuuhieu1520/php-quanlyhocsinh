<?php
namespace App\Routes;

use App\Helper\Router;

return (new Router())
    ->get('/dashboard', 'App\Controllers\Admin\Dashboard@show')
    ->get('/students/show/{classId}', 'App\Controllers\Admin\Students@show')
    ->post('/logout', 'App\Controllers\Admin\Dashboard@logout');

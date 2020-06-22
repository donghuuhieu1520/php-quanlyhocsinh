<?php
namespace App\Routes;

use App\Helper\Router;

return (new Router())
    ->get('/dashboard', 'App\Controllers\Admin\Dashboard@show', 'showDashboard')
    ->get('/students/show/{classId}', 'App\Controllers\Admin\Students@show', 'adminShowStudentByClass')
    ->post('/logout', 'App\Controllers\Admin\Dashboard@logout', 'logout');

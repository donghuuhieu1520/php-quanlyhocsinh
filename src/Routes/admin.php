<?php
namespace App\Routes;

use App\Helper\Router;

return (new Router())
    ->get('/dashboard', 'App\Controllers\Admin\Dashboard@show', 'showDashboard')
    ->get('/students/show/{classId}', 'App\Controllers\Admin\Students@show', 'adminShowStudentByClass')
    ->get('/students/add', 'App\Controllers\Admin\Students@showAdd', 'adminAddStudentToClass')
    ->post('/logout', 'App\Controllers\Admin\Dashboard@logout', 'logout');

<?php
namespace App\Routes;

use App\Helper\Router;

$adminRouter = new Router();
$adminRouter->get('/dashboard', 'App\Controllers\Admin\Dashboard@show')
            ->get('/students/show/{classId}', 'App\Controllers\Admin\Students@show')
            ->post('/logout', 'App\Controllers\Admin\Dashboard@logout');

return $adminRouter;

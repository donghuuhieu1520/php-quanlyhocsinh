<?php

namespace App\Routes\api\v1;

use App\Helper\Router;

return (new Router())
    ->get('/students/{classId}', 'App\Controllers\Admin\Students@getAll')
    ->post('/students', 'App\Controllers\Admin\Students@add', 'apiAddStudentToClass')
    ->delete('/students/{studentId}', 'App\Controllers\Admin\Students@delete')
    ->post('/students/{studentId}', 'App\Controllers\Admin\Students@update');

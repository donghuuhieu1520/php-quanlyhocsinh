<?php

namespace App\Routes\api\v1;

use App\Helper\Router;

return (new Router())
    ->get('/students/listFiles', 'App\Controllers\Admin\Students@listAllFileExcels', 'apiListFileExcel')
    ->get('/students/{classId}', 'App\Controllers\Admin\Students@getAll')
    ->post('/students/upload', 'App\Controllers\Admin\Students@uploadFileExcel', 'apiUploadExcel')
    ->post('/students/deleteFile', 'App\Controllers\Admin\Students@deleteFileExcel', 'apiDeleteFileExcel')
    ->post('/students/viewFile', 'App\Controllers\Admin\Students@viewFileExcel', 'apiViewFileExcel')
    ->post('/students/addFromFile', 'App\Controllers\Admin\Students@addMany', 'apiAddStudentsFromFile')
    ->post('/students', 'App\Controllers\Admin\Students@add', 'apiAddStudentToClass')
    ->delete('/students/{studentId}', 'App\Controllers\Admin\Students@delete')
    ->post('/students/search', 'App\Controllers\Admin\Students@search', 'apiSearchStudent')
    ->post('/students/{studentId}', 'App\Controllers\Admin\Students@update')
    ->post('/changePassword', 'App\Controllers\Admin\ChangePassword@doChangePassword', 'doChangePassword')
    ->get('/rules', 'App\Controllers\Admin\Rules@getAll')
    ->get('/rules/studentToRule/{studentId}', 'App\Controllers\Admin\Rules@getStudentToRule');

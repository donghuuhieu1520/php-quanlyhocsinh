<?php declare(strict_types = 1);

return [
    ['GET', '/', ['App\Controllers\Homepage', 'show']],
    ['GET', '/login', ['App\Controllers\Login', 'show']],
    ['POST', '/login', ['App\Controllers\Login', 'doLogin']],
    ['GET', '/admin/dashboard', ['App\Controllers\Admin\Dashboard', 'show']],
    ['POST', '/admin/logout', ['App\Controllers\Admin\Dashboard', 'logout']],
    ['GET', '/admin/classes/{classId}/students', ['App\Controllers\Admin\Class', 'showStudentList']],
];

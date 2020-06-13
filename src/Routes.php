<?php declare(strict_types = 1);

return [
    ['GET', '/', ['App\Controllers\Homepage', 'show']],
    ['GET', '/login', ['App\Controllers\Login', 'show']],
    ['POST', '/login', ['App\Controllers\Login', 'doLogin']],
    // ['GET', '/{slug}', ['App\Controllers\Page', 'show']],
    ['GET', '/classes', ['App\Controllers\Admin\Students', 'show']],
];

<?php declare(strict_types = 1);

use App\Helper\Router;

Router::get('/', 'App\Controllers\Homepage@show');
Router::get('/login', 'App\Controllers\Login@show');
Router::post('/login', 'App\Controllers\Login@doLogin');
Router::get('/admin/dashboard', 'App\Controllers\Admin\Dashboard@show');
Router::post('/admin/logout', 'App\Controllers\Admin\Dashboard@logout');

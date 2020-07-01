<?php

namespace App\Routes\api\v1;

use App\Helper\Router;

return (new Router())
    ->post('/search', 'App\Controllers\Admin\Rules@search', 'doSearchRule')
    ->post('/', 'App\Controllers\Admin\Rules@add', 'apiAddRules')
    ->get('/', 'App\Controllers\Admin\Rules@getAll', 'apiGetAllRules')
    ->post('/update', 'App\Controllers\Admin\Rules@update', 'apiUpdateRule')
    ->delete('/{ruleId}', 'App\Controllers\Admin\Rules@delete');

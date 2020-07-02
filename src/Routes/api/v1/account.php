<?php

namespace App\Routes\api\v1;

use App\Helper\Router;

return (new Router())
    ->post('/{accountId}/update', '\App\Controllers\Admin\Accounts@update')
    ->post('/', '\App\Controllers\Admin\Accounts@add', 'apiAddNewAccount')
    ->get('/', '\App\Controllers\Admin\Accounts@getAll', 'apiGetAllAccounts')
    ->delete('/{accountId}', '\App\Controllers\Admin\Accounts@delete');

<?php

namespace App\Routes\api\v1;

use App\Helper\Router;

return (new Router())
    ->post('/search', 'App\Controllers\Admin\Rules@search', 'doSearchRule');

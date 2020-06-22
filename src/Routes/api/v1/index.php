<?php

namespace App\Routes\api\v1;

use App\Helper\Router;

return (new Router())
    ->useRoute('/admin', require_once __DIR__ . '/admin.php');

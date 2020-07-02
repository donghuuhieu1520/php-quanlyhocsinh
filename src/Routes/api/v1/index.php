<?php

namespace App\Routes\api\v1;

use App\Helper\Router;

return (new Router())
    ->useRoute('/admin', require_once __DIR__ . '/admin.php')
    ->useRoute('/rules', require_once __DIR__ . '/rule.php')
    ->useRoute('/accounts', require_once __DIR__ . '/account.php');
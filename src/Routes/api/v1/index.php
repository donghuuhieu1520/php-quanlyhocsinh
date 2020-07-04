<?php

namespace App\Routes\api\v1;

use App\Helper\Router;

return (new Router())
    ->useRoute('/admin', require_once __DIR__ . '/admin.php')
    ->useRoute('/rules', require_once __DIR__ . '/rule.php')
    ->useRoute('/studentsToRules', require_once __DIR__ . '/studentsToRule.php')
    ->useRoute('/dashboards', require_once __DIR__ . '/dashboard.php')
    // ->useRoute('/messages', require_once __DIR__ . '/message.php')
    ->useRoute('/accounts', require_once __DIR__ . '/account.php');
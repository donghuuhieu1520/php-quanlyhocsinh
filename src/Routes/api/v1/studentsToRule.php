<?php

namespace App\Routes\api\v1;

use App\Helper\Router;

return (new Router())
    ->post('/search', 'App\Controllers\Admin\StudentsToRules@search', 'doSearchRule')
    ->post('/addStudentToRule', 'App\Controllers\Admin\StudentsToRules@add', 'apiAddStudentToRule');

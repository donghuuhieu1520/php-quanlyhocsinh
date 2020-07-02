<?php
namespace App\Routes;

use App\Helper\Router;

return (new Router())
    ->get('/dashboard', 'App\Controllers\Admin\Dashboard@show', 'showDashboard')
    ->get('/change-password', 'App\Controllers\Admin\ChangePassword@show', 'showChangeAdminPassword')
    ->get('/search-rules', 'App\Controllers\Admin\Rules@showSearchPage', 'showSearchRulesPage')
    ->get('/students/show/{classId}', 'App\Controllers\Admin\Students@show', 'adminShowStudentByClass')
    ->get('/students/add', 'App\Controllers\Admin\Students@showAdd', 'adminAddStudentToClass')
    ->get('/rules/show', 'App\Controllers\Admin\Rules@showManagePage', 'showRuleManage')
    ->get('/accounts/show', 'App\Controllers\Admin\Accounts@showManagePage', 'showAccountManage')
    ->post('/logout', 'App\Controllers\Admin\Dashboard@logout', 'logout');

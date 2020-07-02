<?php
namespace App\Routes;

use App\Helper\Router;

return (new Router())
    ->get('/dashboard', 'App\Controllers\Admin\Dashboard@show', 'showDashboard')
    ->get('/change-password', 'App\Controllers\Admin\ChangePassword@show', 'showChangeAdminPassword')
    ->post('/logout', 'App\Controllers\Admin\Dashboard@logout', 'logout')
    ->get('/search-rules-of-students', 'App\Controllers\Admin\StudentsToRules@showSearchPage', 'showSearchRulesPage')
    ->get('/students/show/{classId}', 'App\Controllers\Admin\Students@show', 'adminShowStudentByClass')
    ->get('/students/add', 'App\Controllers\Admin\Students@showAdd', 'adminAddStudentToClass')
    ->get('/rules/show', 'App\Controllers\Admin\Rules@showManagePage', 'showRuleManage')
    ->get('/accounts/show', 'App\Controllers\Admin\Accounts@showManagePage', 'showAccountManage')
    ->get('/addStudentToRule', 'App\Controllers\Admin\StudentsToRules@showAddStudentToRule', 'showAddStudentToRule')
    ->get('/show-message-management', 'App\Controllers\Admin\Messages@showManagePage', 'showMessageManage');

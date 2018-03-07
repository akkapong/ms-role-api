<?php
/*
 * Define custom routes. File gets included in the router service definition.
 */
// $router = new Phalcon\Mvc\Router();

// $router->addGet("/basic", "Index::basic");
// $router->addGet("/basic-list", "Index::basicList");
// $router->addGet("/test-mongo", "test::mongoInsert");

// return $router;

use Phalcon\Mvc\Router\Group as RouterGroup;

$router->removeExtraSlashes(true);

$router->setDefaults(array(
    'namespace'  => 'Core\Controllers',
    'controller' => 'error',
    'action'     => 'page404'
));

//==========Route for api==========
$role = new RouterGroup(array(
    'namespace' => 'Roles\Controllers'
));

//Route for role

//==== Start : role Section ====//
$role->addGet('/roles', [
    'controller' => 'role',
    'action'     => 'getRole',
]);

$role->addGet('/roles/{id}', [
    'controller' => 'role',
    'action'     => 'getRoledetail',
]);

$role->addPost('/roles', [
    'controller' => 'role',
    'action'     => 'postRole',
]);

$role->addPut('/roles/{id}', [
    'controller' => 'role',
    'action'     => 'putRole',
]);

$role->addDelete('/roles/{id}', [
    'controller' => 'role',
    'action'     => 'deleteRole',
    // 'params'     => 1
]);
//==== End : role Section ====//
$router->mount($role);

$permission = new RouterGroup(array(
    'namespace' => 'Permissions\Controllers'
));

//Route for permission

//==== Start : permission Section ====//
$permission->addGet('/permissions', [
    'controller' => 'permission',
    'action'     => 'getPermission',
]);

$permission->addGet('/permissions/{id}', [
    'controller' => 'permission',
    'action'     => 'getPermissiondetail',
]);

$permission->addPost('/permissions', [
    'controller' => 'permission',
    'action'     => 'postPermission',
]);

$permission->addPut('/permissions/{id}', [
    'controller' => 'permission',
    'action'     => 'putPermission',
]);

$permission->addDelete('/permissions/{id}', [
    'controller' => 'permission',
    'action'     => 'deletePermission',
    // 'params'     => 1
]);
//==== End : permission Section ====//
$router->mount($permission);

return $router;

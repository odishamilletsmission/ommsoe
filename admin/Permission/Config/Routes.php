<?php
if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}
$routes->group('admin', ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('permission', 'Permission\Controllers\Permission::index');
    $routes->post('permission/search','Permission\Controllers\Permission::search');
    $routes->match(['get','post'],'permission/add', 'Permission\Controllers\Permission::add');
    $routes->match(['get','post'],'permission/edit/(:segment)', 'Permission\Controllers\Permission::edit/$1');
    $routes->get('permission/delete/(:segment)',   'Permission\Controllers\Permission::delete/$1');
    $routes->post('permission/delete','Permission\Controllers\Permission::delete');

});
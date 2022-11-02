<?php
namespace Admin\Proceeding\Config;
if(!isset($routes))
{
    $routes = \Config\Services::routes(true);
}

$routes->group('admin', ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('proceeding', 'Proceeding\Controllers\Proceeding::index');
    $routes->post('proceeding/search','Proceeding\Controllers\Proceeding::search');
    $routes->match(['get','post'],'proceeding/add', 'Proceeding\Controllers\Proceeding::add');
    $routes->match(['get','post'],'proceeding/edit/(:segment)', 'Proceeding\Controllers\Proceeding::edit/$1');
    $routes->get('proceeding/delete/(:segment)',   'Proceeding\Controllers\Proceeding::delete/$1');
    $routes->post('proceeding/delete','Proceeding\Controllers\Proceeding::delete');

});

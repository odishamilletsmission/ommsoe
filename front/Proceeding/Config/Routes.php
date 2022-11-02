<?php
if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}
$routes->group('', ['namespace' => 'Front'], function($routes){
    $routes->add('proceeding', 'Proceeding\Controllers\Proceeding::index');
    $routes->post('proceeding/search','Proceeding\Controllers\Proceeding::search');

});
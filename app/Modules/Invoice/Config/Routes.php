<?php
if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}
$routes->group('invoice', ['namespace' => 'App\Modules\Invoice\Controllers'], function($subroutes){
	/*** Route for Dashboard ***/
	$subroutes->add('', 'Dashboard::index');
	$subroutes->add('dashboard', 'Dashboard::index');
});
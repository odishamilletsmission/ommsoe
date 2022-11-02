<?php

namespace Config;

// Create a new instance of our RouteCollection class.
use Admin\Module\Controllers\Module;
use CodeIgniter\Controller;

$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes->get('/', 'Home::index');

//$routes->get('/', 'Auth::login');

$routes->group(env('app.adminRoute'), ['namespace' => 'Admin','filter' => 'login'], function($routes)
{
    $routes->add('/', 'Common\Controllers\Dashboard::index');
    $routes->add('login', 'Common\Controllers\Auth::login');
    $routes->get('logout', 'Common\Controllers\Auth::logout');
    $routes->add('relogin', 'Common\Controllers\Auth::reLogin');
    $routes->get('oldportallogin', 'Common\Controllers\Auth::oldPortalLogin');
    $routes->match(['get','post'],'account/password', 'Common\Controllers\Auth::password');

    $routes->add('district', 'Localisation\Controllers\District::index');
    $routes->post('district/search','Localisation\Controllers\District::search');
    $routes->add('district/block/','Localisation\Controllers\District::block/');
    $routes->get('district/block/(:segment)','Localisation\Controllers\District::block/$1');
    $routes->match(['get','post'],'district/add', 'Localisation\Controllers\District::add');
    $routes->match(['get','post'],'district/edit/(:segment)', 'Localisation\Controllers\District::edit/$1');
    $routes->get('district/delete/(:segment)',   'Localisation\Controllers\District::delete/$1');
    $routes->post('district/delete','Localisation\Controllers\District::delete');

    $routes->add('block', 'Localisation\Controllers\Block::index');
    $routes->add('block/grampanchayat/','Localisation\Controllers\Block::grampanchayat/');
    $routes->get('block/grampanchayat/(:segment)','Localisation\Controllers\Block::grampanchayat/$1');
    $routes->get('block/grampanchayat/(:segment)/(:segment)','Localisation\Controllers\Block::grampanchayat/$1/$2');
    $routes->get('block/cluster/(:segment)','Localisation\Controllers\Block::cluster/$1');

    $routes->post('block/search','Localisation\Controllers\Block::search');

    $routes->match(['get','post'],'block/add', 'Localisation\Controllers\Block::add');
    $routes->match(['get','post'],'block/edit/(:segment)', 'Localisation\Controllers\Block::edit/$1');
    $routes->get('block/delete/(:segment)',   'Localisation\Controllers\Block::delete/$1');
    $routes->post('block/delete','Localisation\Controllers\Block::delete');

    $routes->add('users', 'Users\Controllers\Users::index');
    $routes->post('users/search','Users\Controllers\Users::search');
    $routes->match(['get','post'],'users/add', 'Users\Controllers\Users::add');
    $routes->match(['get','post'],'users/edit/(:segment)', 'Users\Controllers\Users::edit/$1');
    $routes->get('users/delete/(:segment)',   'Users\Controllers\Users::delete/$1');
    $routes->post('users/delete','Users\Controllers\Users::delete');
    $routes->get('users/login/(:segment)','Users\Controllers\Users::login/$1');

    $routes->add('members', 'Users\Controllers\Members::index');
    $routes->post('members/search','Users\Controllers\Members::search');
    $routes->match(['get','post'],'members/add', 'Users\Controllers\Members::add');
    $routes->match(['get','post'],'members/edit/(:segment)', 'Users\Controllers\Members::edit/$1');
    $routes->get('members/delete/(:segment)',   'Users\Controllers\Members::delete/$1');
    $routes->post('members/delete','Users\Controllers\Members::delete');


    $routes->add('usergroup', 'Users\Controllers\Usergroup::index');
    $routes->post('usergroup/search','Users\Controllers\Usergroup::search');
    $routes->match(['get','post'],'usergroup/add', 'Users\Controllers\Usergroup::add');
    $routes->match(['get','post'],'usergroup/edit/(:segment)', 'Users\Controllers\Usergroup::edit/$1');
    $routes->get('usergroup/delete/(:segment)',   'Users\Controllers\Usergroup::delete/$1');
    $routes->post('usergroup/delete','Users\Controllers\Usergroup::delete');
    $routes->match(['get','post'],'usergroup/permission/(:segment)', 'Users\Controllers\Usergroup::permission/$1');


    $routes->match(['get','post'],'menu', 'Menu\Controllers\Menu::index');
    $routes->match(['get','post'],'menu/(:num)', 'Menu\Controllers\Menu::index/$1');
    $routes->match(['get','post'],'menu/add', 'Menu\Controllers\Menu::add');
    $routes->match(['get','post'],'menu/edit/(:segment)', 'Menu\Controllers\Menu::edit/$1');
    $routes->post('menu/deleteMenuItem',   'Menu\Controllers\Menu::deleteMenuItem');
    $routes->get('menu/delete/(:segment)',   'Menu\Controllers\Menu::delete/$1');

    $routes->add('openingbalance','Transaction\Controllers\OpeningBalance::index');

    $routes->get('transaction','Transaction\Controllers\Transaction::index');
    $routes->get('transaction/search','Transaction\Controllers\Transaction::search');
    $routes->post('transaction/upload','Transaction\Controllers\Transaction::upload');
    $routes->get('transaction/downloadtemplate','Transaction\Controllers\Transaction::downloadTemplate');
    $routes->add('transaction/edit/(:num)','Transaction\Controllers\Transaction::edit/$1');
    $routes->add('transaction/add','Transaction\Controllers\Transaction::add');
    $routes->add('transaction/delete/(:num)','Transaction\Controllers\Transaction::delete/$1');
    $routes->add('transaction/refund','Transaction\Controllers\Refund::index');
    $routes->add('transaction/refund/search','Transaction\Controllers\Refund::search');
    $routes->add('transaction/refund/add','Transaction\Controllers\Refund::add');
    $routes->add('transaction/refund/edit/(:num)','Transaction\Controllers\Refund::edit/$1');

    $routes->add('otherreceipt','Transaction\Controllers\OtherReceipt::index');
    $routes->add('otherreceipt/search','Transaction\Controllers\OtherReceipt::search');
    $routes->add('otherreceipt/add','Transaction\Controllers\OtherReceipt::add');
    $routes->add('otherreceipt/edit/(:num)','Transaction\Controllers\OtherReceipt::edit/$1');

    $routes->add('closingbalance','Transaction\Controllers\ClosingBalance::index');
    $routes->get('closingbalance/search','Transaction\Controllers\ClosingBalance::search');
    $routes->add('closingbalance/add','Transaction\Controllers\ClosingBalance::add');
    $routes->add('closingbalance/info/(:num)/(:num)/(:num)/(:num)','Transaction\Controllers\ClosingBalance::info/$1/$2/$3/$4');
    $routes->post('closingbalance/upload','Transaction\Controllers\ClosingBalance::upload');
    $routes->add('closingbalance/district','Transaction\Controllers\ClosingBalance::district');

    $routes->add('component','Component\Controllers\Component::index');
    $routes->post('component/add','Component\Controllers\Component::add');
    $routes->post('component/delete','Component\Controllers\Component::delete');

    $routes->get('component/agency','Component\Controllers\Agency::index');
    $routes->post('component/agency/assign','Component\Controllers\Agency::assign');
    $routes->add('component/budget','Component\Controllers\Budget::index');

    $routes->get('approve','Transaction\Controllers\Approve::index');
    $routes->add('approve/transaction','Transaction\Controllers\Approve::transaction');
    $routes->add('approve/fundreceipt','Transaction\Controllers\Approve::fundReceipt');
    $routes->add('approve/otherreceipt','Transaction\Controllers\Approve::otherReceipt');
    $routes->add('approve/closingbalance','Transaction\Controllers\Approve::closingBalance');
    $routes->get('approve/getform','Transaction\Controllers\Approve::getForm');

    $routes->get('reports','Reports\Controllers\Reports::index');
    $routes->get('reports/mpr','Reports\Controllers\Mpr::index');
    $routes->get('reports/mpr/download','Reports\Controllers\Mpr::index/download');
    $routes->get('reports/bankinterest','Reports\Controllers\BankInterest::index');
    $routes->get('reports/sfp','Reports\Controllers\SFP::index');


    $routes->get('uc/allotment','UC\Controllers\Allotment::index');
    $routes->add('uc/allotment/add','UC\Controllers\Allotment::add');
    $routes->add('uc/allotment/edit','UC\Controllers\Allotment::edit');
    $routes->get('uc/allotment/info','UC\Controllers\Allotment::info');
    $routes->get('uc/submit','UC\Controllers\Submit::index');
    $routes->get('uc/submit/info','UC\Controllers\Submit::info');
    $routes->add('uc/submit/add','UC\Controllers\Submit::add');
    $routes->add('uc/submit/edit','UC\Controllers\Submit::edit');
    $routes->add('uc/submit/upload','UC\Controllers\Submit::upload');
	
	
    $routes->add('mis','MIS\Controllers\MIS::index');
    $routes->add('mis/add','MIS\Controllers\MIS::add');
    $routes->add('mis/edit/(:num)','MIS\Controllers\MIS::edit/$1');
	$routes->add('mis/upload','MIS\Controllers\MIS::upload');
	$routes->post('mis/search','MIS\Controllers\MIS::search');

});
$routes->group('/', ['namespace' => 'Admin'], function($routes){
    $routes->add('profile','Users\Controllers\Profile::index');
    $routes->post('profile/upload','Users\Controllers\Profile::upload');
});
/*
{
    $routes->get('/', 'Pages\Controllers\Home::index');
    $routes->get('page/info/(:segment)',      'Pages\Controllers\Page::info/$1');
*/
    /**
     * ------------------------------------------------------
     * DATABASE ROUTING
     * ------------------------------------------------------
     */
/*
    $db = \Config\Database::connect();
    $builder = $db->table('seo_url');
    $query   = $builder->get();
    $result = $query->getResult();

    foreach( $result as $row )
    {
        $routes->add($row->slug,$row->namespace)  ;
    }
});
*/
$routes->get('api','Api::index');

//$routes->get('/', 'Admin\Common\Controllers\Dashboard::index',['filter' => 'login']);

//$routes->group('', ['namespace' => 'Front'], function($routes){
//    $routes->get('/', 'App\Controllers\Home::index');
//});


/**
     * --------------------------------------------------------------------
     * HMVC Routing
     * --------------------------------------------------------------------
     */

/*foreach(glob(ROOTPATH . 'admin/*', GLOB_ONLYDIR) as $item_dir)
{
	if (file_exists($item_dir . '/Config/Routes.php'))
	{
		
		require_once($item_dir . '/Config/Routes.php');
	}	
}

foreach(glob(ROOTPATH . 'front/*', GLOB_ONLYDIR) as $item_dir)
{
    if (file_exists($item_dir . '/Config/Routes.php'))
    {

        require_once($item_dir . '/Config/Routes.php');
    }
}*/


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

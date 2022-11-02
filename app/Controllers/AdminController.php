<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */

class AdminController extends Controller
{
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['aio','form','date','general','number'];
    public $statuses = ['Not Approved','Approved','Rejected','Not Uploaded','Not Required'];
    public $colors = [
        'warning',
        'success',
        'secondary',
        'danger',
        'primary',
    ];
	

	protected $template;

	protected $user;
	
	protected $session;

	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
        $this->settings = new \Config\Settings();
		$this->template = service('template');
        $this->user = service('user');
		$this->session = service('session');
		$this->uri = service('uri');
		
		//dd($this->template);
		$this->template->set_theme('admin');
		$this->template->set('header',true);
        $this->template->set('site_name',$this->settings->config_site_title);

        $ckfinderdata = [
			'root'  	=> WRITEPATH,
			'baseUrl'   => base_url('writable/uploads')
		];
		$_SESSION['ckfinder']=$ckfinderdata;
		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.: $this->session = \Config\Services::session();
	}

	public function _remap($method, ...$params)
   	{
		
		//print_r($params);
		$router = service('router');
		
		//$controller_full_name = explode('\\', $router->controllerName());
        //$view_folder = strtolower($this->directory . '/' . end($controller_full_name));
        //Checks if it's a 404 or not
        if(!$this->user->checkPermission()){

            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }else if (method_exists($this, $method)) {
			return call_user_func_array(array($this, $method),$params);
        } else {

            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
		
		/*if($this->user->checkLogin()){
			return Modules::run("common/login/index",$params);		
		}else if(!$this->user->checkPermission()){
			return modules::run("common/errors/index",$params);		
		}else if (method_exists($this, $method)){
			return call_user_func_array(array($this, $method), $params);
		}else{
			return modules::run("common/errors/index",$params);
		}*/
		//show_404();
    }
}

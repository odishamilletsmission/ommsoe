<?php
namespace Admin\Common\Controllers;
use App\Controllers\AdminController;
use Admin\Common\Controllers\Leftbar;

class Header extends AdminController
{
	public function __construct()
    {
		$this->settings = new \Config\Settings();
	}
	public function index()
	{
		$data=array();
		
		$data['site_name'] = $this->settings->config_site_title;
		if (is_file(DIR_UPLOAD . $this->settings->config_site_logo)) {
			$data['logo'] = base_url('storage/uploads') . '/' . $this->settings->config_site_logo;
		} else {
			$data['logo'] = '';
		}
		$data['logout'] = admin_url('logout');
		if($this->session->get('temp_user')){
		    $data['relogin']=true;
        }else {
            $data['relogin']=false;
        }
		$data['name']=$this->user->getFirstName();
		if($this->user->isLogged()){
			//$leftbar = new Leftbar(); // Create an instance
			//$data['menu']=$leftbar->index();
            $menu = new \App\Libraries\Menu(); // Create an instance
            $data['menu']=$menu->nav_menu([
                'theme_location'=>'admin',
                'menu_class'     => 'nav-main'
            ]);
		}


		return view('Admin\Common\Views\header',$data);
		
	}
}

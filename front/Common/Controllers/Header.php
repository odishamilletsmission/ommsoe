<?php
namespace Front\Common\Controllers;
use App\Controllers\BaseController;

class Header extends BaseController
{
	public function index()
	{
		//return view('frontend/common/header');
		$data=[];
		
		if (is_file(DIR_UPLOAD . $this->settings->config_site_logo)) {
			$data['logo'] = base_url('uploads') . '/' .  $this->settings->config_site_logo;
		} else {
			$data['logo'] = '';
		}

		$menu = new \App\Libraries\Menu(); // Create an instance
		
		$data['menu']=$menu->nav_menu([
            'theme_location'=>'primary',
            'menu_class'     => 'navbar-nav mx-auto'
        ]);

		if ($this->uri->getSegment(1)) {
			$data['class'] = $this->uri->getSegment(1);
		} else {
			$data['class'] = 'home';
		}
		return $this->template->view('Front\Common\Views\header', $data,true);
		
	}
}

return  __NAMESPACE__ ."\Header";
?>
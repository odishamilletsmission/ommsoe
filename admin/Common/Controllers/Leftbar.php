<?php 
namespace Admin\Common\Controllers;
use App\Controllers\AdminController;

class Leftbar extends AdminController{

	public function __construct()
	{
		$this->user=service('user');
	}
	public function index()
	{
		
		$data=array();
		
		// Dashboard
		$data['menus'][] = array(
			'id'       => 'menu-dashboard',
			'icon'	  => 'md-home',
			'name'	  => lang('Leftbar.text_dashboard'),
			'href'     => admin_url('/'),
			'children' => array()
		);
		
		
		// Pages
		$pages = array();
		
		if ($this->user->hasPermission('access', 'pages/index')) {
			$pages[] = array(
				'name'	  => lang('Leftbar.text_list_page'),
				'href'     => admin_url('pages'),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('access', 'pages/add')) {
			$pages[] = array(
				'name'	  => lang('Leftbar.text_add_page'),
				'href'     => admin_url('pages/add'),
				'children' => array()		
			);	
		}
		
		
		if ($pages) {
			$data['menus'][] = array(
				'id'       => 'menu-pages',
				'icon'	   => 'md-pages', 
				'name'	   => lang('Leftbar.text_page'),
				'href'     => '',
				'children' => $pages
			);
		}
		
		
		// Pages
		/*$posts = array();
		
		if ($this->user->hasPermission('access', 'posts/index')) {
			$posts[] = array(
				'name'	  => lang('Leftbar.text_list_post'),
				'href'     => admin_url('posts'),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('access', 'posts/add')) {
			$posts[] = array(
				'name'	  => lang('Leftbar.text_add_post'),
				'href'     => admin_url('posts/add'),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('access', 'posts/category/index')) {
			$posts[] = array(
				'name'	  => lang('Leftbar.text_category'),
				'href'     => admin_url('posts/category'),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('access', 'posts/tag/index')) {
			$posts[] = array(
				'name'	  => lang('Leftbar.text_tag'),
				'href'     => admin_url('posts/tag'),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('access', 'posts/comment/index')) {
			$posts[] = array(
				'name'	  => lang('Leftbar.text_comment'),
				'href'     => admin_url('posts/comment'),
				'children' => array()		
			);	
		}
		
		if ($posts) {
			$data['menus'][] = array(
				'id'       => 'menu-posts',
				'icon'	   => 'md-pages', 
				'name'	   => lang('Leftbar.text_post'),
				'href'     => '',
				'children' => $posts
			);
		}*/

        // Forms
        $forms = array();

        if ($this->user->hasPermission('access', 'household/index')) {
            $forms[] = array(
                'name'	  => lang('Leftbar.text_household'),
                'href'     => admin_url('household'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'aggriculture/index')) {
            $forms[] = array(
                'name'	  => lang('Leftbar.text_aggriculture'),
                'href'     => admin_url('aggriculture'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'horticulture/index')) {
            $forms[] = array(
                'name'	  => lang('Leftbar.text_horticulture'),
                'href'     => admin_url('horticulture'),
                'children' => array()
            );
        }


        if ($this->user->hasPermission('access', 'livestock/index')) {
            $forms[] = array(
                'name'	  => lang('Leftbar.text_livestock'),
                'href'     => admin_url('livestock'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'fishery/index')) {
            $forms[] = array(
                'name'	  => lang('Leftbar.text_fishery'),
                'href'     => admin_url('fishery'),
                'children' => array()
            );
        }

		if ($this->user->hasPermission('access', 'institution/index')) {
            $forms[] = array(
                'name'	  => lang('Leftbar.text_institution'),
                'href'     => admin_url('institution'),
                'children' => array()
            );
        }

        if ($forms) {
            $data['menus'][] = array(
                'id'       => 'menu-forms',
                'icon'	   => 'md-forms',
                'name'	   => lang('Leftbar.text_form'),
                'href'     => '',
                'children' => $forms
            );
        }

        // Banners
        $banner = array();

        if ($this->user->hasPermission('access', 'banner/index')) {
            $banner[] = array(
                'name'	  => lang('Leftbar.text_all_banner'),
                'href'     => admin_url('banner'),
                'children' => array()
            );
        }


        if ($this->user->hasPermission('access', 'banner/add')) {
            $banner[] = array(
                'name'	  => lang('Leftbar.text_add_banner'),
                'href'     => admin_url('banner/add'),
                'children' => array()
            );
        }


        if ($banner) {
            $data['menus'][] = array(
                'id'       => 'menu-banner',
                'icon'	   => 'md-account-child',
                'name'	   => lang('Leftbar.text_banner'),
                'href'     => '',
                'children' => $banner
            );
        }

        // Dashboard
        $data['menus'][] = array(
            'id'       => 'menu-menu',
            'icon'	  => 'md-menu',
            'name'	  => lang('Leftbar.text_menu'),
            'href'     => admin_url('menu'),
            'children' => array()
        );
		
		// Dashboard
        $data['menus'][] = array(
            'id'       => 'menu-proceeding',
            'icon'	  => 'md-proceeding',
            'name'	  => lang('Leftbar.text_proceeding'),
            'href'     => admin_url('proceeding'),
            'children' => array()
        );
		// localization
		
		$localisation = array();
		
		if ($this->user->hasPermission('access', 'district/index')) {
			$localisation[] = array(
				'name'	   => lang('Leftbar.text_district'),
				'href'     	=> admin_url('district'),
				'children' 	=> array()		
			);
		}
		

		
		if ($this->user->hasPermission('access', 'block/index')) {
			$localisation[] = array(
				'name'	   => lang('Leftbar.text_block'),
				'href'     	=> admin_url('block'),
				'children' 	=> array()		
			);
		}
		
		if ($this->user->hasPermission('access', 'grampanchayat/index')) {
			$localisation[] = array(
				'name'	  => lang('Leftbar.text_grampanchayat'),
				'href'     => admin_url('grampanchayat'),
				'children' => array()		
			);	
		}

        if ($this->user->hasPermission('access', 'cluster/index')) {
            $localisation[] = array(
                'name'	   => lang('Leftbar.text_cluster'),
                'href'     	=> admin_url('cluster'),
                'children' 	=> array()
            );
        }
		
		if ($this->user->hasPermission('access', 'village/index')) {
			$localisation[] = array(
				'name'	   => lang('Leftbar.text_village'),
				'href'     	=> admin_url('village'),
				'children' 	=> array()		
			);
		}
		
		if ($localisation) {
			$data['menus'][] = array(
				'id'       => 'menu-localisations',
				'icon'	   => 'md-localisations', 
				'name'	   => lang('Leftbar.text_localisation'),
				'href'     => '',
				'children' => $localisation
			);
			
		}
		
		// users
		$user = array();
		
		if ($this->user->hasPermission('access', 'user/index')) {
			$user[] = array(
				'name'	  => lang('Leftbar.text_user'),
				'href'     => admin_url('users'),
				'children' => array()		
			);	
		}

        if ($this->user->hasPermission('access', 'users/members/index')) {
            $user[] = array(
                'name'	  => lang('Leftbar.text_member'),
                'href'     => admin_url('members'),
                'children' => array()
            );
        }
		
		if ($this->user->hasPermission('access', 'users/usergroup/index')) {
			$user[] = array(
				'name'	  => lang('Leftbar.text_role'),
				'href'     => admin_url('usergroup'),
				'children' => array()		
			);	
		}

        if ($this->user->hasPermission('access', 'permission/index')) {
            $user[] = array(
                'name'	  => lang('Leftbar.text_permission'),
                'href'     => admin_url('permission'),
                'children' => array()
            );
        }
	
		
		if ($user) {
			$data['menus'][] = array(
				'id'       => 'menu-user',
				'icon'	   => 'md-users', 
				'name'	   => lang('Leftbar.text_users'),
				'href'     => '',
				'children' => $user
			);
		}
		
		
		// System
		$system = array();
		
		if ($this->user->hasPermission('access', 'setting/index')) {
			$system[] = array(
				'name'	  => lang('Leftbar.text_setting'),
				'href'     => admin_url('setting'),
				'children' => array()		
			);	
		}
		
		
		
		
		
		
		if ($this->user->hasPermission('access', 'setting/serverinfo/index')) {
			$system[] = array(
				'name'	  => lang('Leftbar.text_serverinfo'),
				'href'     => admin_url('setting/serverinfo'),
				'children' => array()		
			);	
		}
	
		
		if ($system) {
			$data['menus'][] = array(
				'id'       => 'menu-system',
				'icon'	   => 'md-settings', 
				'name'	   => lang('Leftbar.text_system'),
				'href'     => '',
				'children' => $system
			);
		}

		return view('Admin\Common\Views\leftbar',$data);
	}
}

/* End of file templates.php */
/* Location: ./application/modules/templates/controllers/templates.php */

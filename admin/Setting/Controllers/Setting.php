<?php
namespace Admin\Setting\Controllers;
use Admin\Banner\Models\BannerModel;
use Admin\Pages\Models\PagesModel;
use Admin\Setting\Models\SettingModel;
use Admin\Users\Models\UserModel;
use App\Controllers\AdminController;

class Setting extends AdminController {
	private $error = array();
	
	function __construct(){
        $this->settingModel=new SettingModel();
        $this->userModel=new UserModel();
        $this->settings = service('settings');
	}
	
	public function index(){
		// Init
      	$data = array();
        $this->template->set_meta_title(lang('Setting.heading_title'));
        $this->template->add_package(array('ckeditor','colorbox','select2'),true);

        $data['heading_title'] 	= lang('Setting.heading_title');
        $data['button_save'] = lang('Setting.button_save');
        $data['button_cancel'] = lang('Setting.button_cancel');


        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => lang('banner.heading_title'),
            'href' => admin_url('banner')
        );
		
		
		if ($this->request->getMethod(1) === 'POST' && $this->validateSetting()){

			$this->settingModel->editSetting('config',$this->request->getPost());
			$this->session->setFlashdata('message', 'Settings Saved');
			redirect()->to(current_url());
		}
		
		
		$data['action'] = admin_url('setting');
		$data['cancel'] = admin_url('setting');
        
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->request->getMethod(1) != 'POST') {
			$user_info = $this->userModel->find(1);
		}

        $data['config_background_position']='';
        $data['config_background_repeat']='';
        $data['config_background_attachment']='';
        $data['config_ftp_enable']='';
        $data['config_ssl']='';
        $data['config_date_format']='';
        $data['config_time_format']='';
        $data['config_seo_url']='';
        $data['config_maintenance_mode']='';
        $data['config_display_error']='';
        $data['config_log_error']='';
        foreach($this->settingModel->where('module', 'config')->findAll() as $row) {
            $field=$row->key;
            $value=$row->value;

		    if($this->request->getPost($field)) {
                $data[$field] = $this->request->getPost($field);
            } else if(isset($this->settings->{$field})) {
                $data[$field] = $this->settings->{$field};
            } else {
                $data[$field] = '';
            }
        }

		if ($this->request->getPost('config_site_logo') && is_file(DIR_UPLOAD . $this->request->getPost('config_site_logo'))) {
			$data['thumb_logo'] = resize($this->request->getPost('config_site_logo'), 100, 100);
		} elseif ($this->settings->config_site_logo && is_file(DIR_UPLOAD . $this->settings->config_site_logo)) {
			$data['thumb_logo'] = resize($this->settings->config_site_logo, 100, 100);
		} else {
			$data['thumb_logo'] = resize('no_image.png', 100, 100);
		}

		if ($this->request->getPost('config_site_icon') && is_file(DIR_UPLOAD . $this->request->getPost('config_site_icon'))) {
			$data['thumb_icon'] = resize($this->request->getPost('config_site_icon'), 100, 100);
		} elseif ($this->settings->config_site_icon && is_file(DIR_UPLOAD . $this->settings->config_site_icon)) {
			$data['thumb_icon'] = resize($this->settings->config_site_icon, 100, 100);
		} else {
			$data['thumb_icon'] = resize('no_image.png', 100, 100);
		}
		
		$data['no_image'] = resize('no_image.png', 100, 100);

//        $pageModel=new PagesModel();
//        $data['pages'] = $pageModel->findAll();
        $data['pages'] = [];

		$data['front_themes'] = $this->template->get_themes();

		$front_theme = $this->settings->config_front_theme?$this->settings->config_front_theme:'default';
		
        $data['front_templates'] = $this->template->get_theme_layouts($front_theme);

		if ($this->request->getPost('config_header_image') && is_file(DIR_UPLOAD . $this->request->getPost('config_header_image'))) {
			$data['thumb_header_image'] = resize($this->request->getPost('config_header_image'), 100, 100);
		} elseif ($this->settings->config_header_image && is_file(DIR_UPLOAD . $this->settings->config_header_image)) {
			$data['thumb_header_image'] = resize($this->settings->config_header_image, 100, 100);
		} else {
			$data['thumb_header_image'] = resize('no_image.png', 100, 100);
		}
		
//		$bannerModel=new BannerModel();
//		$data['banners'] = $bannerModel->findAll();
		$data['banners'] = [];

		if ($this->request->getPost('background_image') && is_file(DIR_UPLOAD . $this->request->getPost('background_image'))) {
			$data['thumb_background_image'] = resize($this->request->getPost('background_image'), 100, 100);
		} elseif ($this->settings->config_background_image && is_file(DIR_UPLOAD . $this->settings->config_background_image)) {
			$data['thumb_background_image'] = resize($this->settings->config_background_image, 100, 100);
		} else {
			$data['thumb_background_image'] = resize('no_image.png', 100, 100);
		}
		


		//$this->load->helper('date');
		//printr(tz_list());
		$data['timezone']=tz_list();
		//printr($data['timezone']);
        echo $this->template->view('Admin\Setting\Views\setting',$data);

	}
	
	public function validateSetting(){
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
      $regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
      $regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 
		
		$rules=array(
			'config_site_title' => array(
				'field' => 'config_site_title', 
				'label' => 'Site Title', 
				'rules' => "trim|required"
			),
			'config_site_tagline' => array(
				'field' => 'config_site_tagline', 
				'label' => 'Site Tagline', 
				'rules' => "trim|required"
			),
			'config_meta_title' => array(
				'field' => 'config_meta_title', 
				'label' => 'Meta Title', 
				'rules' => "trim|required"
			),
			'config_site_owner' => array(
				'field' => 'config_site_owner', 
				'label' => 'Site Owner', 
				'rules' => "trim|required"
			),
			'config_address' => array(
				'field' => 'config_address', 
				'label' => 'Site Address', 
				'rules' => "trim|required"
			),
//			'config_country_id' => array(
//				'field' => 'config_country_id',
//				'label' => 'Country',
//				'rules' => "trim|required"
//			),
//			'config_state_id' => array(
//				'field' => 'config_state_id',
//				'label' => 'State',
//				'rules' => "trim|required"
//			),
			'config_email' => array(
				'field' => 'config_email', 
				'label' => 'Email', 
				'rules' => "trim|required|valid_email"
			),
			'config_telephone' => array(
				'field' => 'config_telephone', 
				'label' => 'Telephone', 
				'rules' => "trim|required|numeric"
			),
			'config_pagination_limit_front' => array(
				'field' => 'config_pagination_limit_front', 
				'label' => 'Pagination limit For front', 
				'rules' => "trim|required|numeric"
			),
			'config_pagination_limit_admin' => array(
				'field' => 'config_pagination_limit_admin', 
				'label' => 'pagination limit for admin', 
				'rules' => "trim|required|numeric"
			),
//			'username' => array(
//				'field' => 'username',
//				'label' => 'Username',
//				'rules' => "trim|required|max_length[255]|regex_match[/^$regex$/]"
//			),
//			'password' => array(
//				'field' => 'password',
//				'label' => 'Password',
//				'rules' => 'trim|required|max_length[100]'
//			),
			
		);


		if ($this->validate($rules))  {
			return true;
    	} else {
			$this->error['warning'] = 'Warning';
			dd($this->validator->getErrors());
			return false;
    	}
	}
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */
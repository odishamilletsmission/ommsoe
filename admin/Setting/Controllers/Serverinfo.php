<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Serverinfo extends Admin_Controller {
	private $error = array();
	
	function __construct(){
      parent::__construct();
	}
	public function index(){
		// Init
      $data = array();
		$this->lang->load('serverinfo');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$this->template->add_stylesheet('modules/setting/assets/css/serverinfo.css');
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('text_settings'),
			'href' => base_url('setting')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => base_url('setting/serverinfo')
		);
		
		$data['heading_title'] 	= $this->lang->line('heading_title');
		ob_start();
      phpinfo();
      $pinfo = ob_get_contents();
      ob_end_clean(); 
      $data['phpinfo'] = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$pinfo);

      $this->template->view('serverinfo', $data);
	}
	
		
	public function server_info(){
		$data = array();
        $data['breadcrumb'] = set_crumbs(array('setting' => 'Settings',current_url() => 'Server Info'));
        $this->template->add_stylesheet('/admin/modules/setting/assets/css/server_info.css');

        ob_start();
        phpinfo();
        $pinfo = ob_get_contents();
        ob_end_clean();
         
        $data['pinfo'] = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$pinfo);

        $this->template->view('server_info', $data);
	}
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */
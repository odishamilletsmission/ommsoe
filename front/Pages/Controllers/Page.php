<?php
namespace Front\Pages\Controllers;
use Admin\Pages\Models\PagesModel;
use App\Controllers\BaseController;

class Page extends BaseController
{
	public function __construct(){
		
		$this->pageModel = new PagesModel(); 
        $this->uri = service('uri');
		$this->user = service('user');
		$this->settings = service('settings');
	}
	public function index($id=0) {
		
        $Page = $this->pageModel->find($id);
		
        if (isset($Page) && !empty($Page)){
			if ($Page->status != 'published'){
				if ($Page->status != 'draft' || ($Page->status == 'draft' &&  ! $this->user->isLogged())){
					throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
				}
			}
         	$this->template->set('page_id', $Page->id);
        	$data['heading_title'] = $Page->title;
        	$data['meta_title'] = $Page->meta_title;
         	//$data['content'] = html_entity_decode($Page->content, ENT_QUOTES, 'UTF-8');
            $data['content'] = do_shortcode(html_entity_decode($Page->content, ENT_QUOTES, 'UTF-8'));

            if ($Page->feature_image && is_file(DIR_UPLOAD . $Page->feature_image)) {
				$data['feature_image'] = base_url('uploads') . '/' . $Page->feature_image;
			} else {
				$data['feature_image'] = '';
			}
			$this->template->add_package(array('particle'),true);

			$this->template->set_meta_description($Page->meta_description)
                           ->set_meta_keywords($Page->meta_keywords)->set_meta_title($Page->meta_title);
			$this->template->set_layout($Page->layout);
			$this->template->set('site_name',$this->settings->config_site_title);
			$data['layout']=$Page->layout;
			$this->template->set('header',true);
			
		 	return $this->template->view('Front\Pages\Views\page', $data);
        
      }else{
          throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
      }
	}
	
	public function info($id=0){
		$Page = $this->pageModel->find($id);
		
        if (isset($Page) && !empty($Page)){
			if ($Page->status != 'published'){
				if ($Page->status != 'draft' || ($Page->status == 'draft' &&  ! $this->user->isLogged())){
					throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
				}
			}
         	$this->template->set('page_id', $Page->id);
        	$data['heading_title'] = $Page->title;
        	$data['meta_title'] = $Page->meta_title;
         	$data['content'] = html_entity_decode($Page->content, ENT_QUOTES, 'UTF-8');
			$this->template->set_meta_description($Page->meta_description)
                           ->set_meta_keywords($Page->meta_keywords)->set_meta_title($Page->meta_title);
			$this->template->set_layout($Page->layout);
			$this->template->set('site_name',$this->settings->config_site_title);
			
			$data['layout']=$Page->layout;
			
			$this->template->set('header',false);
			//$data['header']=false;
			return $this->template->view('Front\Pages\Views\page', $data);
		 	//return view('Frontend/Pages/page', $data);
        
      }else{
          throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
      }
	}

	
	
}

//return  __NAMESPACE__ ."\Auth";
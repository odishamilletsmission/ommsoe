<?php
namespace Front\Pages\Controllers;
use Admin\Pages\Models\PagesModel;
use Admin\Banner\Models\BannerModel;
use App\Controllers\BaseController;

class Home extends BaseController
{
	private $error = array();
    function __construct(){
		$this->pageModel = new PagesModel(); 
        $this->bannerModel = new BannerModel();        
	}
    public function index()
	{
		$data=array();
		$Page = $this->pageModel->find(1);
       // print_r($Page);
      	if (isset($Page) && !empty($Page)){  
         	if ($Page->status != 'published'){
            	if ($Page->status != 'draft'){
                    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
				}
         	}
         	$this->template->set_meta_title($Page->title);
         	//$data['heading_title'] = $Page->title;
			$data['content'] = html_entity_decode($Page->content, ENT_QUOTES, 'UTF-8');
            $this->template->set('site_name',$this->settings->config_site_title);

        }

		$data['banners'] = [];

        foreach ($this->bannerModel->getBannerImages(3) as $banner) {
            $data['banners'][] = [
                'image' => upload_url($banner['image']),
                'title' => $banner['title'],
                'link' => $banner['link'],
                'description' => $banner['description'],
            ];
		}
		
		$data['sliders'] = [];

        foreach ($this->bannerModel->getBannerImages(4) as $slider) {
            $data['sliders'][] = [
                'image' => upload_url($slider['image']),
                'title' => $slider['title'],
                'link' => $slider['link'],
                'description' => $slider['description'],
            ];
		}
		$this->template->add_package(['lightgallery'],true);
        
		$this->template->add_javascript('themes/default/assets/js/plugins.js');
		$this->template->set('header',true);
		$this->template->set('home',true);
		return $this->template->view('Front\Pages\Views\home',$data);
		
	}

}

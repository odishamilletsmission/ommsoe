<?php
namespace Front\Banner\Controllers;
use Admin\Banner\Models\BannerModel;
use App\Controllers\BaseController;

class Banner extends BaseController
{
	private $error = array();
    private $bannerModel;
    function __construct(){
		$this->bannerModel = new BannerModel();
	}

	public function getGallery($attr){
        $this->template->add_package(['lightgallery'],true);
        extract($attr);
        $data['sliders'] = [];
        foreach ($this->bannerModel->getBannerImages($id) as $slider) {
            $data['sliders'][] = [
                'image' => upload_url($slider['image']),
                'title' => $slider['title'],
                'link' => $slider['link'],
                'description' => $slider['description'],
            ];
        }
        //printr($data['sliders']);
        return $this->template->view('Front\Banner\Views\gallery',$data,true);

    }

    public function getSlider($attr){
        extract($attr);
        $data['banners'] = [];

        foreach ($this->bannerModel->getBannerImages(3) as $banner) {
            $data['banners'][] = [
                'image' => upload_url($banner['image']),
                'title' => $banner['title'],
                'link' => $banner['link'],
                'description' => $banner['description'],
            ];
        }
        //printr($data['sliders']);
        return $this->template->view('Front\Banner\Views\slider',$data,true);

    }

}

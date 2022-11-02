<?php
namespace Admin\Banner\Controllers;
use App\Controllers\AdminController;
use Admin\Banner\Models\BannerModel;

/**
 * Created by Wassan.
 * User: Niranjan
 * Date: 18-12-2021
 * Time: 03:11 PM
 */
class Banner extends AdminController {

    private $error = array();

    function __construct(){
        $this->bannerModel=new BannerModel();
    }
    public function index(){
        $this->template->set_meta_title(lang('Banner.heading_title'));
        return $this->getList();
    }

    protected function getList() {

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => lang('banner.heading_title'),
            'href' => admin_url('banner')
        );

        $this->template->add_package(array('datatable'),true);

        $data['add'] = admin_url('banner/add');
        $data['delete'] = admin_url('banner/delete');
        $data['datatable_url'] = admin_url('banner/search');

        $data['heading_title'] = lang('banner.heading_title');

        $data['text_list'] = lang('banner.text_list');
        $data['text_no_results'] = lang('banner.text_no_results');
        $data['text_confirm'] = lang('banner.text_confirm');

        $data['button_add'] = lang('banner.button_add');
        $data['button_edit'] = lang('banner.button_edit');
        $data['button_delete'] = lang('banner.button_delete');

        if(isset($this->error['warning'])){
            $data['error'] 	= $this->error['warning'];
        }

        if ($this->request->getPost('selected')) {
            $data['selected'] = (array)$this->request->getPost('selected');
        } else {
            $data['selected'] = array();
        }

        return $this->template->view('Admin\Banner\Views\banner', $data);
    }

    public function search() {
        $requestData= $_REQUEST;
        $totalData = $this->bannerModel->getTotalBanners();
        $totalFiltered = $totalData;

        $filter_data = array(
            'filter_search' => $requestData['search']['value'],
            'order'  		 => $requestData['order'][0]['dir'],
            'sort' 			 => $requestData['order'][0]['column'],
            'start' 			 => $requestData['start'],
            'limit' 			 => $requestData['length']
        );
        $totalFiltered = $this->bannerModel->getTotalBanners($filter_data);

        $filteredData = $this->bannerModel->getBanners($filter_data);

        $datatable=array();
        foreach($filteredData as $result) {

            $action  = '<div class="btn-group btn-group-sm pull-right">';
            $action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('banner/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
            $action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('banner/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
            $action .= '</div>';

            $datatable[]=array(
                '<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
                $result->title,
                $result->status?'Enabled':'Disabled',
                $action
            );

        }
        //printr($datatable);
        $json_data = array(
            "draw"            => isset($requestData['draw']) ? intval( $requestData['draw'] ):1,
            "recordsTotal"    => intval( $totalData ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $datatable
        );
		return $this->response->setContentType('application/json')
								->setJSON($json_data);
        
    }

    public function add(){
        $this->template->set_meta_title(lang('Banner.heading_title'));

        if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){

            $id=$this->bannerModel->addBanner($this->request->getPost());
            $this->session->setFlashdata('message', 'Banner Saved Successfully.');

            return redirect()->to(admin_url('banner'));


        }
        $this->getForm();
    }

    public function edit(){
       $this->template->set_meta_title(lang('Banner.heading_title'));

         if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){
            $id=$this->uri->getSegment(4);

            $this->bannerModel->editBanner($id,$this->request->getPost());
			$this->session->setFlashdata('message', 'Banner Updated Successfully.');

            return redirect()->to(admin_url('banner'));

        }
        $this->getForm();
    }

    public function delete(){
		if ($this->request->getPost('selected')){
			$selected = $this->request->getPost('selected');
		}else{
			$selected = (array) $this->uri->getSegment(4);
		}
		$this->bannerModel->deleteBanner($selected);
		
		//$this->slugModel->whereIn('route_id', $selected)->delete();
		
		$this->session->setFlashdata('message', 'Banner deleted Successfully.');
		return redirect()->to(admin_url('banner'));
	
    }

    protected function getForm(){

        $this->template->add_package(array('ckeditor','ckfinder','tablednd','colorbox'),true);

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => lang('Banner.heading_title'),
            'href' => admin_url('banner')
        );

        $_SESSION['isLoggedIn'] = true;

        $data['heading_title'] 	= lang('Banner.heading_title');
        $data['text_form'] = $this->uri->getSegment(3) ? "Banner Edit" : "Banner Add";
        $data['text_image'] =lang('Banner.text_image');
        $data['text_none'] = lang('Banner.text_none');
        $data['text_clear'] = lang('Banner.text_clear');
        $data['cancel'] = admin_url('banner');


        $data['button_save'] = lang('Banner.button_save');
        $data['button_cancel'] = lang('Banner.button_cancel');


        if(isset($this->error['warning'])){
            $data['error'] 	= $this->error['warning'];
        }

        if ($this->uri->getSegment(4) && ($this->request->getMethod(true) != 'POST')) {
           $banner_info = $this->bannerModel->getBanner($this->uri->getSegment(4));
        }
		//printr($banner_info);
        foreach($this->bannerModel->getFieldNames('banner') as $field) {
            if($this->request->getPost($field)) {
                $data[$field] = $this->request->getPost($field);
            } else if(isset($banner_info->{$field}) && $banner_info->{$field}) {
                $data[$field] = html_entity_decode($banner_info->{$field},ENT_QUOTES, 'UTF-8');
            } else {
                $data[$field] = '';
            }
        }


        // Images
        if ($this->request->getPost('banner_image')) {
            $banner_images = $this->request->getPost('banner_image');
        } elseif ($this->uri->getSegment(4)) {
            $banner_images = $this->bannerModel->getBannerImages($this->uri->getSegment(4));
        } else {
            $banner_images = array();
        }

        $data['banner_images'] = array();
        //printr($banner_images);
        foreach ($banner_images as $banner_image) {
            if (is_file(DIR_UPLOAD . $banner_image['image'])) {
                $image = $banner_image['image'];
                $thumb = $banner_image['image'];
            } else {
                $image = '';
                $thumb = 'no_image.png';
            }

            $data['banner_images'][] = array(
                'image'      => $image,
                'thumb'      => resize($thumb, 100, 100),
                'title'		 => $banner_image['title'],
                'link'		 => $banner_image['link'],
                'description'=> $banner_image['description'] 
            );
        }
		//printr( $data['banner_images']);
        $data['no_image'] = resize('no_image.png', 100, 100);

        echo $this->template->view('Admin\Banner\Views\bannerForm',$data);
    }

    protected function validateForm() {

        $validation =  \Config\Services::validation();
		$id=$this->uri->getSegment(4);
		
		$rules = $this->bannerModel->validationRules;
		
        if ($this->validate($rules)){
			return true;
    	}
		else{
			//printr($validation->getErrors());
			$this->error['warning']="Warning: Please check the form carefully for errors!";
			return false;
    	}
        return !$this->error;
    }
}

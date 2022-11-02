<?php
namespace Admin\Pages\Controllers;
use Admin\Pages\Models\PagesModel;
use App\Controllers\AdminController;
use Admin\Pages\Models\SlugModel;

class Pages extends AdminController{
	private $error = array();
	private $pagesModel;
	private $slugModel;
	
	public function __construct(){
		$this->pagesModel = new PagesModel();
		$this->slugModel=new SlugModel();
	}
	
	public function index(){
		$this->template->set_meta_title(lang('Pages.heading_title'));
		return $this->getList();  
	}
	
	public function add(){
		
		$this->template->set_meta_title(lang('Pages.heading_title'));
		
		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){	
			
			$userid=$this->pagesModel->insert($this->request->getPost());
			
			//slug insert
			$slugdata=[
				'slug'=>$this->request->getPost('slug'),
				'namespace'=>'Pages\Controllers\Page::index/'.$userid,
				'route_id'=>$userid
			];
			$this->slugModel->insert($slugdata);
			
			$this->session->setFlashdata('message', 'Page Saved Successfully.');
			
			return redirect()->to(base_url('admin/pages'));
		}
		$this->getForm();
	}
	
	public function edit(){
		
		
		$this->template->set_meta_title(lang('Pages.heading_title'));
		
		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){	
			$id=$this->uri->getSegment(4);
			
			$this->pagesModel->update($id,$this->request->getPost());
			
			$this->slugModel->where('route_id', $id)->delete();
			
			$slugdata=[
				'slug'=>$this->request->getPost('slug'),
				'namespace'=>'Pages\Controllers\Page::index/'.$id,
				'route_id'=>$id
			];
			$this->slugModel->insert($slugdata);
			
			$this->session->setFlashdata('message', 'Page Updated Successfully.');
		
			return redirect()->to(base_url('admin/pages'));
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
			$selected = $this->request->getPost('selected');
		}else{
			$selected = (array) $this->uri->getSegment(4);
		}
		$this->pagesModel->delete($selected);
		
		$this->slugModel->whereIn('route_id', $selected)->delete();
		
		$this->session->setFlashdata('message', 'Page deleted Successfully.');
		return redirect()->to(base_url('admin/pages'));
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Pages.heading_title'),
			'href' => admin_url('pages')
		);
		
		$this->template->add_package(array('datatable'),true);

		$data['add'] = admin_url('pages/add');
		$data['delete'] = admin_url('pages/delete');
		$data['datatable_url'] = admin_url('pages/search');

		$data['heading_title'] = lang('Pages.heading_title');
		
		$data['text_list'] = lang('Pages.text_list');
		$data['text_no_results'] = lang('Pages.text_no_results');
		$data['text_confirm'] = lang('Pages.text_confirm');
		
		$data['button_add'] = lang('Pages.button_add');
		$data['button_edit'] = lang('Pages.button_edit');
		$data['button_delete'] = lang('Pages.button_delete');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Pages\Views\page', $data);
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->pagesModel->getTotalPages();
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $requestData['order'][0]['column'],
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->pagesModel->getTotalPages($filter_data);
			
		$filteredData = $this->pagesModel->getPages($filter_data);
		
		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('pages/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('pages/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';
			
			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
				$result->title,
				base_url($result->slug),
				$result->layout,
				$result->status,
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
	
	public function vedit(){
		if ($this->request->getMethod(1) === 'POST'){	
			$id=$this->uri->getSegment(4);
			$vdata=array(
				'content'=>$this->request->getPost('html')
			);
			$this->pagesModel->update($id,$vdata);
			echo "Page Updated succussfully";
			exit;

		}
		//$this->template->add_package(array('visualeditor'),true);
		$this->template->set('header',false);
		$id=$this->uri->getSegment(4);
		$data['pagelink']=base_url("page/info/$id");
		$data['action']=admin_url("pages/vedit/$id");
        $data['close']=admin_url("pages/edit/$id");
		//print_r($data);
		return $this->template->view('Admin\Pages\Views\visualEditor',$data);
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('ckeditor','ckfinder','colorbox','visualeditor'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Pages.heading_title'),
			'href' => admin_url('pages')
		);
		
		//printr($_SESSION);
		$_SESSION['isLoggedIn'] = true;
        
		$data['heading_title'] 	= lang('Pages.heading_title');
		$data['text_form'] = $this->uri->getSegment(3) ? "Page Edit" : "Page Add";
		$data['text_image'] =lang('Pages.text_image');
		$data['text_none'] = lang('Pages.text_none');
		$data['text_clear'] = lang('Pages.text_clear');
		$data['cancel'] = admin_url('pages');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(4) && ($this->request->getMethod(true) != 'POST')) {
			$page_info = $this->pagesModel->find($this->uri->getSegment(4));
			$seo_info = $this->slugModel->where('route_id', $this->uri->getSegment(4))->find();
		}
		
		foreach($this->pagesModel->getFieldNames('pages') as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($page_info->{$field}) && $page_info->{$field}) {
				$data[$field] = html_entity_decode($page_info->{$field},ENT_QUOTES, 'UTF-8');
			} else {
				$data[$field] = '';
			}
		}
		
		/*foreach($this->pagesModel->getFieldNames('seo_url') as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($seo_info->{$field}) && $seo_info->{$field}) {
				$data[$field] = $seo_info->{$field};
			} else {
				$data[$field] = '';
			}
		}*/
		
		
		
		if ($this->request->getPost('content')) {
			$data['content'] = $this->request->getPost('content');
		} elseif (!empty($page_info)) {
			$data['content'] = html_entity_decode(stripslashes($page_info->content), ENT_QUOTES, 'UTF-8');
		} else {
			$data['content'] = '';
		}

		if ($this->request->getPost('feature_image')) {
			$data['feature_image'] = $this->request->getPost('feature_image');
		} elseif (!empty($page_info)) {
			$data['feature_image'] = $page_info->feature_image;
		} else {
			$data['feature_image'] = '';
		}
		
		if ($this->request->getPost('feature_image') && is_file(DIR_UPLOAD . $this->request->getPost('feature_image'))) {
			$data['thumb_feature_image'] = resize($this->request->getPost('feature_image'), 100, 100);
		} elseif (!empty($page_info) && is_file(DIR_UPLOAD . $page_info->feature_image)) {
			$data['thumb_feature_image'] = resize($page_info->feature_image, 100, 100);
		} else {
			$data['thumb_feature_image'] = resize('no_image.png', 100, 100);
		}
		
		$data['no_image'] = resize('no_image.png', 100, 100);
		$settings = new \Config\Settings();
		//printr($settings);
		$front_theme = $settings->config_front_theme;
		//echo $front_theme;
		$data['layouts']=$this->template->get_theme_layouts($front_theme);
		//printr($data['layouts']);
		$data['parents'] = $this->pagesModel->getParents($this->uri->getSegment(4));
		
		//printr($data['parents']);
		echo $this->template->view('Admin\Pages\Views\pageForm',$data);
	}
	
	protected function validateForm() {
		//printr($_POST);
		$validation =  \Config\Services::validation();
		$id=$this->uri->getSegment(4);
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
		$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
		$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 

		$rules = $this->pagesModel->validationRules;
		
		/*$rules=array(
			
			'title' => array(
				'label' => 'Title', 
				'rules' => 'trim|required|max_length[100]'
			),
			
			'slug' => array(
				'field' => 'slug', 
				'label' => 'Slug', 
				'rules' => "trim|required|max_length[255]|regex_match[/^$regex$/]|is_unique[seo_url.slug,route_id,{id}]"
			),
			'meta_title' => array(
				'field' => 'meta_title', 
				'label' => 'Meta Title', 
				'rules' => 'trim'
			), 
			'meta_description' => array(
				'field' => 'meta_description', 
				'label' => 'Meta Description', 
				'rules' => 'trim'
			),
			'meta_keywords' => array(
				'field' => 'meta_keywords', 
				'label' => 'Meta Keywords', 
				'rules' => 'trim'
			),
			'status' => array(
				'field' => 'status', 
				'label' => 'Status', 
				'rules' => 'trim|required'
			),
			
		);*/
		
		//$validation->setRules($rules);
		
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
	
	public function unique_slug_check($slug, $id = ''){
		$slug_info = $this->general_model->getSlug($slug);
		
		if ($slug_info && $slug_info->route != 'pages/index/' . $id) {
			$this->form_validation->set_message('unique_slug_check', 'This {field} provided is already in use.');
			return FALSE;
		}else{
			return TRUE;
		}
   }
}

/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */
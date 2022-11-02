<?php
namespace Admin\Permission\Controllers;
use App\Controllers\AdminController;
use Admin\Permission\Models\PermissionModel;

class Permission extends AdminController{
	private $error = array();
	private $permissionModel;

	public function __construct(){
		$this->permissionModel=new PermissionModel();
    }
	
	public function index(){
		$this->template->set_meta_title(lang('Permission.heading_title'));
		return $this->getList();  
	}
	
	public function add(){
		
		$this->template->set_meta_title(lang('Permission.heading_title'));
		
		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){	
			//printr($this->request->getPost());
			//exit;
			$this->permissionModel->insert($this->request->getPost());
            $this->session->setFlashdata('message', 'Permission Saved Successfully.');
			
			return redirect()->to(base_url('admin/permission'));
		}
		$this->getForm();
	}
	
	public function edit(){
		
		
		$this->template->set_meta_title(lang('Permission.heading_title'));
		
		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){	
			$id=$this->uri->getSegment(4);
            
			$this->permissionModel->update($id,$this->request->getPost());
            $this->session->setFlashdata('message', 'Permission Updated Successfully.');
		
			return redirect()->to(base_url('admin/permission'));
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
			$selected = $this->request->getPost('selected');
		}else{
			$selected = (array) $this->uri->getSegment(4);
		}
		$this->permissionModel->delete($selected);
		$this->session->setFlashdata('message', 'Permission deleted Successfully.');
		return redirect()->to(base_url('admin/permission'));
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Permission.heading_title'),
			'href' => admin_url('permission')
		);
		
		$this->template->add_package(array('datatable'),true);

		$data['add'] = admin_url('permission/add');
		$data['delete'] = admin_url('permission/delete');
		$data['datatable_url'] = admin_url('permission/search');

		$data['heading_title'] = lang('Permission.heading_title');
		
		$data['text_list'] = lang('Permission.text_list');
		$data['text_no_results'] = lang('Permission.text_no_results');
		$data['text_confirm'] = lang('Permission.text_confirm');
		
		$data['button_add'] = lang('Permission.button_add');
		$data['button_edit'] = lang('Permission.button_edit');
		$data['button_delete'] = lang('Permission.button_delete');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Permission\Views\permission', $data);
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->permissionModel->getTotal();
		$totalFiltered = $totalData;
		
		$filter_data = array(

			'filter_search' => $requestData['search']['value'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $requestData['order'][0]['column'],
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->permissionModel->getTotal($filter_data);
			
		$filteredData = $this->permissionModel->getAll($filter_data);
		//printr($filteredData);
		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
            $action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('permission/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('permission/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';
			
			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
				$result->name,
				$result->description,
                $result->status?'Enable':'Disable',
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
	
	protected function getForm(){
		
		$this->template->add_package(array('select2','flatpickr','ckfinder'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Permission.heading_title'),
			'href' => admin_url('permission')
		);
		

		$data['heading_title'] 	= lang('Permission.heading_title');
		$data['text_form'] = $this->uri->getSegment(4) ? "Permission Edit" : "Permission Add";
		$data['cancel'] = admin_url('permission');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(4) && ($this->request->getMethod(true) != 'POST')) {
			$permission_info = $this->permissionModel->find($this->uri->getSegment(4));
		}
		
		foreach($this->permissionModel->getFieldNames('permission') as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($permission_info->{$field}) && $permission_info->{$field}) {
				$data[$field] = html_entity_decode($permission_info->{$field},ENT_QUOTES, 'UTF-8');
			} else {
				$data[$field] = '';
			}
		}

		echo $this->template->view('Admin\Permission\Views\permissionForm',$data);
	}
	
	protected function validateForm() {
		//printr($_POST);
		$validation =  \Config\Services::validation();
		$id=$this->uri->getSegment(4);
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
		$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
		$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 

		$rules = $this->permissionModel->validationRules;

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

/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */
<?php
namespace Admin\Proceeding\Controllers;
use App\Controllers\AdminController;
use Admin\Proceeding\Models\ProceedingModel;

class Proceeding extends AdminController{
	private $error = array();
	private $proceedingModel;

	public function __construct(){
		$this->proceedingModel=new ProceedingModel();
    }
	
	public function index(){
		$this->template->set_meta_title(lang('Proceeding.heading_title'));
		return $this->getList();  
	}
	
	public function add(){
		
		$this->template->set_meta_title(lang('Proceeding.heading_title'));
		
		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){	
			//printr($this->request->getPost());
			//exit;
			$this->proceedingModel->insert($this->request->getPost());
            $this->session->setFlashdata('message', 'Proceeding Saved Successfully.');
			
			return redirect()->to(base_url('admin/proceeding'));
		}
		$this->getForm();
	}
	
	public function edit(){
		
		
		$this->template->set_meta_title(lang('Proceeding.heading_title'));
		
		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){	
			$id=$this->uri->getSegment(4);
            
			$this->proceedingModel->update($id,$this->request->getPost());
            $this->session->setFlashdata('message', 'Proceeding Updated Successfully.');
		
			return redirect()->to(base_url('admin/proceeding'));
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
			$selected = $this->request->getPost('selected');
		}else{
			$selected = (array) $this->uri->getSegment(4);
		}
		$this->proceedingModel->delete($selected);
		$this->session->setFlashdata('message', 'Proceeding deleted Successfully.');
		return redirect()->to(base_url('admin/proceeding'));
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Proceeding.heading_title'),
			'href' => admin_url('proceeding')
		);
		
		$this->template->add_package(array('datatable'),true);

		$data['add'] = admin_url('proceeding/add');
		$data['delete'] = admin_url('proceeding/delete');
		$data['datatable_url'] = admin_url('proceeding/search');

		$data['heading_title'] = lang('Proceeding.heading_title');
		
		$data['text_list'] = lang('Proceeding.text_list');
		$data['text_no_results'] = lang('Proceeding.text_no_results');
		$data['text_confirm'] = lang('Proceeding.text_confirm');
		
		$data['button_add'] = lang('Proceeding.button_add');
		$data['button_edit'] = lang('Proceeding.button_edit');
		$data['button_delete'] = lang('Proceeding.button_delete');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Proceeding\Views\proceeding', $data);
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->proceedingModel->getTotal();
		$totalFiltered = $totalData;
		
		$filter_data = array(

			'filter_search' => $requestData['search']['value'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $requestData['order'][0]['column'],
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->proceedingModel->getTotal($filter_data);
			
		$filteredData = $this->proceedingModel->getAll($filter_data);
		//printr($filteredData);
		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
            $action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('proceeding/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('proceeding/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';
			
			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
				$result->name,
				$result->letter_no,
                $result->letter_date,
                $result->attachment,
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
			'text' => lang('Proceeding.heading_title'),
			'href' => admin_url('proceeding')
		);
		

		$data['heading_title'] 	= lang('Proceeding.heading_title');
		$data['text_form'] = $this->uri->getSegment(4) ? "Proceeding Edit" : "Proceeding Add";
		$data['cancel'] = admin_url('proceeding');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(4) && ($this->request->getMethod(true) != 'POST')) {
			$proceeding_info = $this->proceedingModel->find($this->uri->getSegment(4));
		}
		
		foreach($this->proceedingModel->getFieldNames('proceeding') as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($proceeding_info->{$field}) && $proceeding_info->{$field}) {
				$data[$field] = html_entity_decode($proceeding_info->{$field},ENT_QUOTES, 'UTF-8');
			} else {
				$data[$field] = '';
			}
		}

		echo $this->template->view('Admin\Proceeding\Views\proceedingForm',$data);
	}
	
	protected function validateForm() {
		//printr($_POST);
		$validation =  \Config\Services::validation();
		$id=$this->uri->getSegment(4);
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
		$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
		$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 

		$rules = $this->proceedingModel->validationRules;

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
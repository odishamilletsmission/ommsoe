<?php
namespace Admin\Users\Controllers;
use App\Controllers\AdminController;
use Admin\Users\Models\MemberModel;

class Members extends AdminController{
	private $error = array();
	private $memberModel;

	public function __construct(){
		$this->memberModel=new MemberModel();
    }
	
	public function index(){
		$this->template->set_meta_title(lang('Members.heading_title'));
		return $this->getList();  
	}
	
	public function add(){
		
		$this->template->set_meta_title(lang('Members.heading_title'));
		
		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){	
			//printr($this->request->getPost());
			//exit;
			$this->memberModel->insert($this->request->getPost());
            $this->session->setFlashdata('message', 'Member Saved Successfully.');
			
			return redirect()->to(base_url('admin/members'));
		}
		$this->getForm();
	}
	
	public function edit(){
		
		
		$this->template->set_meta_title(lang('Users.heading_title'));
		
		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){	
			$id=$this->uri->getSegment(4);
            
			$this->memberModel->update($id,$this->request->getPost());
            $this->session->setFlashdata('message', 'Member Updated Successfully.');
		
			return redirect()->to(base_url('admin/members'));
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
			$selected = $this->request->getPost('selected');
		}else{
			$selected = (array) $this->uri->getSegment(4);
		}
		$this->memberModel->delete($selected);
		$this->session->setFlashdata('message', 'Members deleted Successfully.');
		return redirect()->to(base_url('admin/members'));
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Members.heading_title'),
			'href' => admin_url('members')
		);
		
		$this->template->add_package(array('datatable'),true);

		$data['add'] = admin_url('members/add');
		$data['delete'] = admin_url('members/delete');
		$data['datatable_url'] = admin_url('members/search');

		$data['heading_title'] = lang('Members.heading_title');
		
		$data['text_list'] = lang('Members.text_list');
		$data['text_no_results'] = lang('Members.text_no_results');
		$data['text_confirm'] = lang('Members.text_confirm');
		
		$data['button_add'] = lang('Members.button_add');
		$data['button_edit'] = lang('Members.button_edit');
		$data['button_delete'] = lang('Members.button_delete');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Users\Views\member', $data);
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->memberModel->getTotal();
		$totalFiltered = $totalData;
		
		$filter_data = array(

			'filter_search' => $requestData['search']['value'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $requestData['order'][0]['column'],
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->memberModel->getTotal($filter_data);
			
		$filteredData = $this->memberModel->getAll($filter_data);
		//printr($filteredData);
		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
            $action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('members/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('members/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';
			
			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
				$result->name,
				$result->designation,
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
		
		$this->template->add_package(array('select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Members.heading_title'),
			'href' => admin_url('members')
		);
		

		$data['heading_title'] 	= lang('Members.heading_title');
		$data['text_form'] = $this->uri->getSegment(4) ? "Members Edit" : "Members Add";
		$data['cancel'] = admin_url('members');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(4) && ($this->request->getMethod(true) != 'POST')) {
			$member_info = $this->memberModel->find($this->uri->getSegment(4));
		}
		
		foreach($this->memberModel->getFieldNames('member') as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($member_info->{$field}) && $member_info->{$field}) {
				$data[$field] = html_entity_decode($member_info->{$field},ENT_QUOTES, 'UTF-8');
			} else {
				$data[$field] = '';
			}
		}

        $data['designations'] = [
            ''=>'Select Designation',
            'dpc'=>'District Coordinator',
            'dpm'=>'District Program Manager',
            'spm'=>'State Program Manager',
            'dpmu_acc'=>'DPMU Accountant',
            'fld_ast'=>'Field Assistant',
            'mkt_ofc'=>'Marketing Officer',
            'grp_dsg'=>'Graphics Designer',
            'rs_tm'=>'Research Team',
            'it_tm'=>'IT Team',
            'rc'=>'Regional Coordinator',
            'st_ac'=>'State Accountant',
            'fao'=>'FAO Manager',
            'st_co'=>'State Coordinator',
            'event_co'=>'Event Coordinator',
            'thematic_expert'=>'Thematic Expert'
        ];



		echo $this->template->view('Admin\Users\Views\memberForm',$data);
	}
	
	protected function validateForm() {
		//printr($_POST);
		$validation =  \Config\Services::validation();
		$id=$this->uri->getSegment(4);
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
		$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
		$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 

		$rules = $this->memberModel->validationRules;

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
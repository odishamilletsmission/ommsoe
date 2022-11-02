<?php
namespace Admin\Localisation\Controllers;
use App\Controllers\AdminController;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use CodeIgniter\API\ResponseTrait;

class District extends AdminController{
	private $error = array();
	private $districtModel;

	use ResponseTrait;
	
	public function __construct(){
		$this->districtModel=new DistrictModel();
	}
	
	public function index(){
		$this->template->set_meta_title(lang('District.heading_title'));
		return $this->getList();  
	}
	
	public function add(){
		
		$this->template->set_meta_title(lang('District.heading_title'));
		
		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){	
			
			$id=$this->districtModel->insert($this->request->getPost());
			
			$this->session->setFlashdata('message', 'District Saved Successfully.');
			
			return redirect()->to(base_url('admin/district'));
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->template->set_meta_title(lang('District.heading_title'));
		
		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){	
			$id=$this->uri->getSegment(4);
			
			$this->districtModel->update($id,$this->request->getPost());
			
			$this->session->setFlashdata('message', 'District Updated Successfully.');
		
			return redirect()->to(base_url('admin/district'));
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
			$selected = $this->request->getPost('selected');
		}else{
			$selected = (array) $this->uri->getSegment(4);
		}
		$this->districtModel->delete($selected);
		
		$this->session->setFlashdata('message', 'District deleted Successfully.');
		return redirect()->to(base_url('admin/district'));
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('District.heading_title'),
			'href' => admin_url('district')
		);
		
		$this->template->add_package(array('datatable'),true);

		$data['add'] = admin_url('district/add');
		$data['delete'] = admin_url('district/delete');
		$data['datatable_url'] = admin_url('district/search');

		$data['heading_title'] = lang('District.heading_title');
		
		$data['text_list'] = lang('District.text_list');
		$data['text_no_results'] = lang('District.text_no_results');
		$data['text_confirm'] = lang('District.text_confirm');
		
		$data['button_add'] = lang('District.button_add');
		$data['button_edit'] = lang('District.button_edit');
		$data['button_delete'] = lang('District.button_delete');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Localisation\Views\district', $data);
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->districtModel->getTotals();
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $requestData['order'][0]['column'],
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->districtModel->getTotals($filter_data);
			
		$filteredData = $this->districtModel->getAll($filter_data);
		
		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('district/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('district/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';
			
			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
				$result->code,
				$result->name,
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
		
		$this->template->add_package(array('colorbox'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('District.heading_title'),
			'href' => admin_url('district')
		);
		
		//printr($_SESSION);
		$_SESSION['isLoggedIn'] = true;
        
		$data['heading_title'] 	= lang('District.heading_title');
		$data['text_form'] = $this->uri->getSegment(3) ? "District Edit" : "District Add";
		$data['cancel'] = admin_url('district');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(4) && ($this->request->getMethod(true) != 'POST')) {
			$district_info = $this->districtModel->find($this->uri->getSegment(4));
		}
		
		foreach($this->districtModel->getFieldNames('district') as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($district_info->{$field}) && $district_info->{$field}) {
				$data[$field] = html_entity_decode($district_info->{$field},ENT_QUOTES, 'UTF-8');
			} else {
				$data[$field] = '';
			}
		}
	
		echo $this->template->view('Admin\Localisation\Views\districtForm',$data);
	}
	
	protected function validateForm() {
		//printr($_POST);
		$validation =  \Config\Services::validation();
		$id=$this->uri->getSegment(4);
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
		$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
		$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 

		$rules = $this->districtModel->validationRules;

		if ($this->validate($rules)){
			return true;
    	}
		else{
			//printr($validation->getErrors());
			$this->error['warning']="Warning: Please check the form carefully for errors!";
			return false;
    	}
		//return !$this->error;
	}
	
	public function block($district=''){
        $blockModel = new BlockModel();

        if($this->request->getGet('district_id')){
            $district = $this->request->getGet('district_id');
        }
        $filter = [
            'district_id' => $district,
        ];
        
        if($this->request->getGet('fund_agency_id')){
            $fund_agency_id = $this->request->getGet('fund_agency_id');
            $filter['fund_agency_id'] = $fund_agency_id;
        }

        $blocks = $blockModel->where($filter)->findAll();

        return $this->respond($blocks);
	}

	public function fundAgency($fund_agency_id=''){

        if($this->request->getGet('fund_agency_id')){
            $fund_agency_id = $this->request->getGet('fund_agency_id');
        }

        $districts = $this->districtModel->getDistrictsByFundAgency($fund_agency_id);

        return $this->respond($districts);
	}
}

/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */
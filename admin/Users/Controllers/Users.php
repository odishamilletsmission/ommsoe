<?php
namespace Admin\Users\Controllers;
use Admin\Localisation\Models\BlockModel;
use App\Controllers\AdminController;
use Admin\Localisation\Models\DistrictModel;
use Admin\Users\Models\UserGroupModel;
use Admin\Users\Models\UserModel;

class Users extends AdminController{
	private $error = array();
	private $userModel;
	private $slugModel;
	
	public function __construct(){
		$this->userModel=new UserModel();
    }
	
	public function index(){
		$this->template->set_meta_title(lang('Users.heading_title'));
		return $this->getList();  
	}
	
	public function add(){
		
		$this->template->set_meta_title(lang('Users.heading_title'));
		
		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){

            $userid=$this->userModel->insert($this->request->getPost());
		    //central_user
            /*$appuser=$this->userModel->addCentralUser($this->request->getPost('firstname'));

            if($appuser){
		        $data=$this->request->getPost();
		        $data['central_appuser_id']=$appuser['id'];
                $data['central_appuser_token']=$appuser['token'];
                $userid=$this->userModel->insert($data);

            }*/

			$this->session->setFlashdata('message', 'User Saved Successfully.');

			return redirect()->to(base_url('admin/users'));
		}
		$this->getForm();
	}
	
	public function edit(){
		
		
		$this->template->set_meta_title(lang('Users.heading_title'));
		
		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){	
			$id=$this->uri->getSegment(4);

			$this->userModel->update($id,$this->request->getPost());

			//$this->userModel->updateAssign($id,$this->request->getPost('form_assign'));

			$this->session->setFlashdata('message', 'User Updated Successfully.');
		
			return redirect()->to(base_url('admin/users'));
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
			$selected = $this->request->getPost('selected');
		}else{
			$selected = (array) $this->uri->getSegment(4);
		}
		$this->userModel->delete($selected);
        foreach($selected as $id){
//            $user=$this->userModel->find($id);
//            $this->userModel->deleteCentralUser($user->central_appuser_id);
        }

		
		$this->session->setFlashdata('message', 'User deleted Successfully.');
		return redirect()->to(base_url('admin/users'));
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Users.heading_title'),
			'href' => admin_url('users')
		);
		
		$this->template->add_package(array('datatable'),true);

		$data['add'] = admin_url('users/add');
		$data['delete'] = admin_url('users/delete');
		$data['datatable_url'] = admin_url('users/search');

		$data['heading_title'] = lang('Users.heading_title');
		
		$data['text_list'] = lang('Users.text_list');
		$data['text_no_results'] = lang('Users.text_no_results');
		$data['text_confirm'] = lang('Users.text_confirm');
		
		$data['button_add'] = lang('Users.button_add');
		$data['button_edit'] = lang('Users.button_edit');
		$data['button_delete'] = lang('Users.button_delete');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Users\Views\user', $data);
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->userModel->getTotal();
		$totalFiltered = $totalData;
		
		$filter_data = array(

			'filter_search' => $requestData['search']['value'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $requestData['order'][0]['column'],
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->userModel->getTotal($filter_data);
			
		$filteredData = $this->userModel->getAll($filter_data);

		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
            $action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('users/login/'.$result->id).'"><i class="fa fa-key"></i></a>';
            $action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('users/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('users/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';
			
			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
				$result->username,
				$result->role,
				$result->district,
                $result->block,
				$result->enabled,
				$action
			);
	
		}

		$json_data = array(
			"draw"            => isset($requestData['draw']) ? intval( $requestData['draw'] ):1,
			"recordsTotal"    => intval( $totalData ),
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => $datatable
		);
		
		return $this->response->setContentType('application/json')
								->setJSON($json_data);
		
	}

	public function login(){
	    $id=$this->uri->getSegment(4);
        $user = $this->userModel->find($id);

        $this->session->set('temp_user',$this->user->getUser());
        $this->session->set('user',$user);
        $this->user->assignUserAttr($user);
        return redirect()->to(base_url('admin'));
    }
	
	protected function getForm(){
		
		$this->template->add_package(array('select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Users.heading_title'),
			'href' => admin_url('users')
		);
		

		$data['heading_title'] 	= lang('Users.heading_title');
		$data['text_form'] = $this->uri->getSegment(3) ? "User Edit" : "User Add";
		$data['text_image'] =lang('Users.text_image');
		$data['text_none'] = lang('Users.text_none');
		$data['text_clear'] = lang('Users.text_clear');
		$data['cancel'] = admin_url('users');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(4) && ($this->request->getMethod(true) != 'POST')) {
			$user_info = $this->userModel->find($this->uri->getSegment(4));
		}
		
		foreach($this->userModel->getFieldNames('user') as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($user_info->{$field}) && $user_info->{$field}) {
				
				$data[$field] = html_entity_decode($user_info->{$field},ENT_QUOTES, 'UTF-8');
				if($field=="gp"){
					$data[$field]=explode(",",$user_info->{$field});
				}
			} else {
				$data[$field] = '';
			}
		}

        $user_group_model = new UserGroupModel();
		$data['user_groups'] =  $user_group_model->findAll();

        $districtModel=new DistrictModel();
        $data['districts'] = $districtModel->getAll();

        $data['blocks'] = [];
        if(isset($data['district_id']) && $data['district_id']){
            $blockModel = new BlockModel();
            $data['blocks'] = $blockModel->where(['district_id'=>$data['district_id']])->findAll();
        }

		echo $this->template->view('Admin\Users\Views\userForm',$data);
	}
	
	protected function validateForm() {
		//printr($_POST);
		$validation =  \Config\Services::validation();
		$id=$this->uri->getSegment(4);
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
		$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
		$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 

		$rules = $this->userModel->validationRules;

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
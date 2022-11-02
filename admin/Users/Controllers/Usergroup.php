<?php
namespace Admin\Users\Controllers;
use Admin\Permission\Models\PermissionModel;
use Admin\Users\Models\UserGroupModel;
use App\Controllers\AdminController;

class Usergroup extends AdminController {
	private $error = array();
    private $usergroupModel;
    private $permissionModel;

	public function __construct(){
        $this->usergroupModel=new UserGroupModel();
        $this->permissionModel=new PermissionModel();
	}
	
	public function index(){
        $this->template->set_meta_title(lang('Users.heading_title'));
        return $this->getList();
    }

    public function add(){

        $this->template->set_meta_title(lang('Usergroup.heading_title'));

        if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){
            //printr($this->request->getPost());
            //exit;
            $this->usergroupModel->insert($this->request->getPost());
            $this->session->setFlashdata('message', 'Member Saved Successfully.');

            return redirect()->to(base_url('admin/usergroup'));
        }
        $this->getForm();
    }

    public function edit(){


        $this->template->set_meta_title(lang('Users.heading_title'));

        if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){
            $id=$this->uri->getSegment(4);

            $this->usergroupModel->update($id,$this->request->getPost());
            $this->session->setFlashdata('message', 'Member Updated Successfully.');

            return redirect()->to(base_url('admin/usergroup'));
        }
        $this->getForm();
    }

    public function delete(){
        if ($this->request->getPost('selected')){
            $selected = $this->request->getPost('selected');
        }else{
            $selected = (array) $this->uri->getSegment(4);
        }
        $this->usergroupModel->delete($selected);
        $this->session->setFlashdata('message', 'Usergroup deleted Successfully.');
        return redirect()->to(base_url('admin/usergroup'));
    }

    protected function getList() {

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => lang('Usergroup.heading_title'),
            'href' => admin_url('usergroup')
        );

        $this->template->add_package(array('datatable'),true);

        $data['add'] = admin_url('usergroup/add');
        $data['delete'] = admin_url('usergroup/delete');
        $data['datatable_url'] = admin_url('usergroup/search');

        $data['heading_title'] = lang('Usergroup.heading_title');

        $data['text_list'] = lang('Usergroup.text_list');
        $data['text_no_results'] = lang('Usergroup.text_no_results');
        $data['text_confirm'] = lang('Usergroup.text_confirm');

        $data['button_add'] = lang('Usergroup.button_add');
        $data['button_edit'] = lang('Usergroup.button_edit');
        $data['button_delete'] = lang('Usergroup.button_delete');

        if(isset($this->error['warning'])){
            $data['error'] 	= $this->error['warning'];
        }

        if ($this->request->getPost('selected')) {
            $data['selected'] = (array)$this->request->getPost('selected');
        } else {
            $data['selected'] = array();
        }

        return $this->template->view('Admin\Users\Views\userGroup', $data);
    }

    public function search() {
        $requestData= $_REQUEST;
        $totalData = $this->usergroupModel->getTotal();
        $totalFiltered = $totalData;

        $filter_data = array(

            'filter_search' => $requestData['search']['value'],
            'order'  		 => $requestData['order'][0]['dir'],
            'sort' 			 => $requestData['order'][0]['column'],
            'start' 			 => $requestData['start'],
            'limit' 			 => $requestData['length']
        );
        $totalFiltered = $this->usergroupModel->getTotal($filter_data);

        $filteredData = $this->usergroupModel->getAll($filter_data);
        //printr($filteredData);
        $datatable=array();
        foreach($filteredData as $result) {

            $action  = '<div class="btn-group btn-group-sm pull-right">';
            $action .= 		'<a class="btn btn-sm btn-info" href="'.admin_url('usergroup/permission/'.$result->id).'"><i class="fa fa-list-alt"></i></a>';

            $action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('usergroup/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
            $action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('usergroup/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
            $action .= '</div>';

            $datatable[]=array(
                '<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
                $result->name,
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
            'text' => lang('Usergroup.heading_title'),
            'href' => admin_url('usergroup')
        );


        $data['heading_title'] 	= lang('Usergroup.heading_title');
        $data['text_form'] = $this->uri->getSegment(4) ? "Usergroup Edit" : "Usergroup Add";
        $data['cancel'] = admin_url('usergroup');

        if(isset($this->error['warning'])){
            $data['error'] 	= $this->error['warning'];
        }

        if ($this->uri->getSegment(4) && ($this->request->getMethod(true) != 'POST')) {
            $usergroup_info = $this->usergroupModel->find($this->uri->getSegment(4));
        }
        //printr($data['permissions']);
        foreach($this->usergroupModel->getFieldNames('user_group') as $field) {
            if($this->request->getPost($field)) {
                $data[$field] = $this->request->getPost($field);
            } else if(isset($usergroup_info->{$field}) && $usergroup_info->{$field}) {
                $data[$field] = html_entity_decode($usergroup_info->{$field},ENT_QUOTES, 'UTF-8');
            } else {
                $data[$field] = '';
            }
        }

        echo $this->template->view('Admin\Users\Views\userGroupForm',$data);
    }

    public function permission(){
        $id = $this->uri->getSegment(4);
        $data['user_group_id']=$id;

        if ($this->request->getMethod(1) === 'POST'){

            $this->usergroupModel->addUserGroupPermission($id,$this->request->getPost());
            $this->session->setFlashdata('message', 'Permission Updated Successfully.');

            return redirect()->to(base_url('admin/usergroup'));
        }
        if((int)$id) {
            $usergroup_info = $this->usergroupModel->find($id);
            $data['text_form'] = $usergroup_info->name ." Permission";
            $data['cancel'] = admin_url('usergroup');
            $data['id']=$id;
            $data['gpermissions'] = (array)$this->permissionModel->get_modules_with_permission($id);
            if(empty($data['permissions'])) {
                $data['permissions'] = NULL;
            }
            echo $this->template->view('Admin\Users\Views\userGroupPermissionForm',$data);
        }else {

        }
    }

    protected function getFormOld(){

        $this->template->add_package(array('select2'),true);

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => lang('Usergroup.heading_title'),
            'href' => admin_url('usergroup')
        );


        $data['heading_title'] 	= lang('Usergroup.heading_title');
        $data['text_form'] = $this->uri->getSegment(4) ? "Usergroup Edit" : "Usergroup Add";
        $data['cancel'] = admin_url('usergroup');

        if(isset($this->error['warning'])){
            $data['error'] 	= $this->error['warning'];
        }

        if ($this->uri->getSegment(4) && ($this->request->getMethod(true) != 'POST')) {
            $usergroup_info = $this->usergroupModel->find($this->uri->getSegment(4));
        }

        foreach($this->usergroupModel->getFieldNames('user_group') as $field) {
            if($this->request->getPost($field)) {
                $data[$field] = $this->request->getPost($field);
            } else if(isset($usergroup_info->{$field}) && $usergroup_info->{$field}) {
                $data[$field] = html_entity_decode($usergroup_info->{$field},ENT_QUOTES, 'UTF-8');
            } else {
                $data[$field] = '';
            }
        }
        helper('filesystem');
		$ignore = array(
			'Login::index',
			'logout/index',
			'forgot-password',
			'Leftbar::index',
			'column_top/index',
			'errors/index',
			'Footer::index',
			'Header::index'
		);

		//$files = glob(DIR_ADMIN_MODULE . '*/controllers/*.php');
        $modules = directory_map(DIR_ADMIN_MODULE);

        $controllers=array();
        foreach($modules as $key=>$module){
            $modulename=stripslashes($key);
            $controller_info=[];
		    foreach($module as $ckey=>$contrs) {

                if ($ckey == "Controllers\\") {
                    foreach ($contrs as $cont) {
                        $controller = pathinfo($cont, PATHINFO_FILENAME);

                        $namespace = "Admin\\$key$ckey$controller";
                        $class = new $namespace();
                        $methods = $this->get_access_methods($class);

                        $con_method = [];
                        foreach ($methods as $cmethod) {
                            if (!in_array($controller . '::' . $cmethod, $ignore)) {
                                $con_method[] = $namespace . '::' . $cmethod;
                            }
                        }
                        if($con_method) {
                            $controller_info[] = array(
                                'controller' => $controller,
                                'methods' => $con_method
                            );
                        }

                    }
                }

            }
            $controllers[$modulename]=$controller_info;



        }
        $data['permissions']=$controllers;

        if ($this->request->getPost('permissions')) {
            $data['access'] = $this->request->getPost('permissions');
        } elseif ($usergroup_info->permissions) {
            $data['access'] = json_decode($usergroup_info->permissions, true);
        } else {
            $data['access'] = array();
        }
        echo $this->template->view('Admin\Users\Views\userGroupForm',$data);
    }

    protected function get_access_methods($class){

        $returnArray = array();
        //echo $class;
        foreach(get_class_methods($class) as $method){
            //echo $class;
            $reflect = new \ReflectionMethod($class, $method);
            if($reflect->isPublic() && !$reflect->isConstructor())
            {
                array_push($returnArray,$method);
            }
        }
        if($parent_class = get_parent_class($class)){
            $thismethod = get_class_methods($parent_class);
            $resthis_method = array_diff($returnArray, $thismethod);

        }
        else{
            $resthis_method = $returnArray;
        }

        return $resthis_method;
    }

    protected function validateForm() {
        //printr($_POST);
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
        $regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
        $regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor

        $rules = $this->usergroupModel->validationRules;

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
<?php
namespace Admin\Menu\Controllers;
use App\Controllers\AdminController;
use Admin\Menu\Models\MenuModel;
use Admin\Pages\Models\PagesModel;

class Menu extends AdminController {
    private $error = array();
    private $menuModel;
    private $menu;
	public function __construct(){
        $this->menuModel=new MenuModel();
        $this->menu = new \App\Libraries\Menu(); // Create an instance

	}
	
	public function index(){
		$data = array();
		$this->template->set_meta_title(lang('Menu.heading_title'));
		$this->template->add_package(array('jquerynestable','sweetalert'),true);

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => lang('Menu.heading_title'),
            'href' => admin_url('menu')
        );

		$data['menu_groups'] = $menu_groups = $this->menuModel->getMenuGroups();
		
		$count=$this->uri->getTotalSegments();

		if($this->uri->getSegment(3)){
			$menu_group_id = $this->uri->getSegment(3);
		}else if($count==2){
			$menu_group_id =$menu_groups?$menu_groups[0]['id']:0;
		}else{
			$menu_group_id = 0;
		}
		
		$data['menu_group_id'] = $menu_group_id;
		
		$data['text_form_group'] = $menu_group_id ? 'Save Menu' : 'Create Menu';

        if ($this->request->getMethod(1) === 'POST' && $this->validateMenuForm() ){

			$menugroup_data=array(
				"title"=>$this->request->getPost('menu_name'),
                "theme_location"=>$this->request->getPost('theme_location'),
				"status"=>1
			);
			
			
			
			if($this->request->getPost('menu_group_id')){
				//update menugroup
				$menu_group_id=$this->request->getPost('menu_group_id');
				$this->menuModel->editMenuGroup($menu_group_id,$menugroup_data);
				
				$itemdatas=(array)$this->request->getPost('item');
				//printr($itemdatas);
				//exit;
				foreach($itemdatas as $key=>$item){
					$menuitem=array(
						"title"=>$item['title'],
						"url"=>$item['url'],
						"class"=>$item['class'],
						"target"=>isset($item['target'])?1:0
					);
					//printr($menuitem);
					$this->menuModel->editMenuItem($key,$menuitem);
				
				}
				//printr($this->input->post('menu_data'));
				//exit;
				if($this->request->getPost('menu_data')){
					$menu = json_decode($this->request->getPost('menu_data'), true, 64);
					$this->update_position(0, $menu);
				}
				$message="Menu Updated Successfully";	
			}else{
				//add menugroup
				$menu_group_id=$this->menuModel->addMenuGroup($menugroup_data);
				$message="Menu Added Successfully";	
			}
            $this->session->setFlashdata('message', $message);

            return redirect()->to(admin_url('menu/'.$menu_group_id));


		}

		$menugroup = $this->menuModel->getMenuGroup($menu_group_id);
		
		if (!empty($menugroup)) {
			$data['menu_name'] = $menugroup['title'];
		} else {
			$data['menu_name'] = '';
		}

        if (!empty($menugroup)) {
            $data['theme_location'] = $menugroup['theme_location'];
        } else {
            $data['theme_location'] = '';
        }

		$data['theme_locations']=array(
            ""=>"Select Location",
		    "primary"=>"Primary",
            "footer"=>"Footer",
            "admin"=>"Admin"
        );
		$menulist =$this->menuModel->getMenuItems($menu_group_id);
		
		$treelist =$this->menu->get_menu_nested($menulist);
		
		$data['menu']=$this->menu->list_menu_nav($treelist);
		
		//$data['action'] = admin_url("menu/save_menu");
		//printr($data['menu_groups']);

		//for pages
//        $pageModel=new PagesModel();
//		$data['pages'] = $pageModel->getPages();
        $data['pages'] = [];

		return $this->template->view('Admin\Menu\Views\menu', $data);
	}
	
	public function add() {
		
		if ($this->request->getMethod(1) === 'POST'){
			
			$json=array();

			if (!$json) {
				if($this->request->getPost('checked')){
					$menu_type=$this->request->getPost('menu_type');
					$li="";
					foreach($this->request->getPost('checked') as $item){
						$sort=$this->menuModel->getMaxSortorder($_POST['menu_group_id']);
						$menuitem=array(
							"menu_group_id"=>$this->request->getPost('menu_group_id'),
							"menu_type"=>$menu_type,
							"title"=>$item['title'],
							"url"=>$item['url'],
							"target"=>0,
							"class"=>'',
							"item_id"=>$item['id'],
							"parent_id"=>0,
							"sort_order"=>$sort+1
						);
						$menu_id=$this->menuModel->addMenuItem($menuitem);
						$li.='<li class="dd-item" data-id="'.$menu_id.'">'.$this->get_label($menuitem,$menu_id).'</li>';
						
					}
				}
				
			
				if($this->request->getPost('checked')){
					$json['menu']=array(
						"status"=>1,
						"li"=>$li,
						"msg"=>"Menu added Successfully",
					);
				} else {
					$json['menu']=array(
						"status"=>2,
						"msg"=>"Add menu error.",
					);
				}
				
			}
			echo json_encode($json);
			exit;
			
		}
	}
	
	public function edit() {
		
		if ($this->input->server('REQUEST_METHOD') === 'POST'){	
			
			$json=array();
			
			if(!$this->validateForm()){
				if(isset($this->error['warning']))
				{
					$json['error'] 	= $this->error['warning'];
				}
				if(isset($this->error['server_errors'])){
					$json['server_errors'] 	= $this->error['server_errors'];
				}
			}
			if (!$json) {
				$menu_id=$this->uri->segment(4);
				$this->menu_model->editMenuItem($this->uri->segment(4),$this->input->post());
				$json['menu']=array(
					"title"=>$this->input->post('title'),
					"url"=>$this->input->post('url'),
					"class"=>$this->input->post('class'),
				);
			}
			echo json_encode($json);
			exit;
			
		}else{
			$this->getListForm();
		}
	}
	
	public function delete(){
		$menu_group_id=(int)$this->uri->getSegment(4);
        //echo $menu_group_id;
		if($menu_group_id){
			$delete=$this->menuModel->deleteMenuGroup($menu_group_id);
            $this->session->setFlashdata('message', 'Menu deleted Successfully.');
            return redirect()->to(admin_url('menu'));

		}
		
	}
	
	
	protected function getListForm() {
		
		$data['text_form'] = $this->uri->segment(3) ? 'Edit Menu' : 'Add Menu';
		
		if (!$this->uri->segment(4)) {
			$data['action'] = admin_url("menu/add");
		} else {
			$data['action'] = admin_url("menu/edit/".$this->uri->segment(4));
		}
		
		
		if ($this->uri->segment(4) && ($this->input->server('REQUEST_METHOD') != 'POST')) {
			$menu_info = $this->menu_model->getMenuItem($this->uri->segment(4));
		}
		
		if ($this->input->post('title')) {
			$data['title'] = $this->input->post('title');
		} elseif (!empty($menu_info)) {
			$data['title'] = $menu_info['title'];
		} else {
			$data['title'] = '';
		}
		
		if ($this->input->post('url')) {
			$data['url'] = $this->input->post('url');
		} elseif (!empty($menu_info)) {
			$data['url'] = $menu_info['url'];
		} else {
			$data['url'] = '';
		}
		
		if ($this->input->post('class')) {
			$data['class'] = $this->input->post('class');
		} elseif (!empty($menu_info)) {
			$data['class'] = $menu_info['class'];
		} else {
			$data['class'] = '';
		}
		
		
		$this->load->view('menuForm', $data);
	}
	
	public function deleteMenuItem() {
		if (isset($_POST['menu_id'])) {
			$menu_id = (int)$_POST['menu_id'];

            $max_id = $this->menuModel->selectMax('id')->get()->getFirstRow()->id;

			$this->menuModel->where(['parent_id'=>$menu_id])->set(['parent_id'=>0,'sort_order'=>$max_id])->update();

			$delete=$this->menuModel->deleteMenuItem($menu_id);
			if ($delete) {
				$response['success'] = 1;
			} else {
				$response['success'] = 0;
			}
			echo json_encode($response);
		}
	}
	
	public function save_menu() {
		if(isset($_POST['menu'])){
			$menu = json_decode($_POST['menu'], true, 64);
			$this->update_position(0, $menu);
		}
	}

	/**
	 * Recursive function for save menu position
	 */
	private function update_position($parent, $children) {
		$i = 1;
		foreach ($children as $k => $v) {
			$menu_id = (int)$children[$k]['id'];
			$node_info_array = array();
			$node_info_array['parent_id'] =  $parent;
			$node_info_array['sort_order'] = $i;
	
			$this->menuModel->updateMenuItemsOrder($menu_id,$node_info_array);
			if (isset($children[$k]['children'][0])) {
				$this->update_position($menu_id, $children[$k]['children']);
			}
			$i++;
		}
	}
	
	protected function validateMenuForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(3);
        $rules = $this->menuModel->validationRules;



        if ($this->validate($rules)){
            return true;
        }
        else{
            //printr($validation->getErrors());
            $this->error['warning']="Warning: Please check the form carefully for errors!";
            $this->error['server_errors'] = $validation->getErrors();

            return false;
        }
        return !$this->error;



	}
	
	protected function validateForm() {
	
		$rules=array(
			'title' => array(
				'field' => 'title', 
				'label' => 'Menu Name', 
				'rules' => 'trim|required|max_length[100]'
			),
			'url' => array(
				'field' => 'url', 
				'label' => 'Menu URL', 
				'rules' => 'trim|required'
			),
		);
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run($this) == TRUE){
			return true;
    	}
		else
		{
			$this->error['warning']="Warning: Please check the form carefully for errors! ";
			$this->error['server_errors'] = $this->form_validation->error_array();
			return false;
    	}
		return !$this->error;
	}
	
	private function get_label($row,$id) {
		$label =
		'<div class="dd-handle">'.
			'<div class="title float-left">'.$row['title'].'</div>'.
			'<div class="float-right">'.$row['menu_type'] .
				'<a data-toggle="collapse" href="#collapse'.$id.'" title="Edit Menu" aria-expanded="false" aria-controls="collapse'.$id.'"><i class="fa fa-angle-down"></i></a>'.
			'</div>'.
		'</div>'.
		'<div class="collapse" id="collapse'.$id.'">'.
			'<div class="block block-content ">'.$this->editform($row,$id).
				'<a href="javacript:void(0)" class="delete-menu" title="Delete Menu">Remove</a>';
			'</div>'.
		'</div>';	
		return $label;
	}

	public function editform($item,$id){
   		$html='<input type="hidden" name="item['.$id.'][item_id]" value="'.$id.'">';		
				
   		if($item['menu_type'] == "custom"){
   			$html.='<div class="form-group">
					<label for="slug">URL</label>
					<input type="text" name="item['.$id.'][url]" value="'.$item['url'].'" class="form-control" placeholder="URL">		
				</div>';
   		}else{
				$html.='<input type="hidden" name="item['.$id.'][url]" value="'.$item['url'].'">';
			}				
			$html.='<div class="form-group">
					<label for="slug">Navigation Label</label>
					<input type="text" name="item['.$id.'][title]" value="'.$item['title'].'" class="form-control" placeholder="Label">		
				</div>
				
				<div class="form-group">
					<label for="slug">CSS Class</label>
					<input type="text" name="item['.$id.'][class]" value="'.$item['class'].'" class="form-control" placeholder="Class">		
				</div>
				<div class="form-group">
					<div class="checkbox">
						 <input type="checkbox" name="item['.$id.'][target]" value="true" '.($item['target'] ? "checked='checked'":"").'>
						 <label for="checkbox1">Open link in a new tab</label>
					</div>
				</div>';
			

   		return $html;

   }
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */
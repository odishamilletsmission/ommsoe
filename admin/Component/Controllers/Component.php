<?php 
namespace Admin\Component\Controllers;
use Admin\Component\Models\ComponentModel;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use App\Controllers\AdminController;
use App\Traits\TreeTrait;

class Component extends AdminController {

    use TreeTrait;
    public function index() {
        $data = [];

        if($this->request->getMethod(1)=='POST'){

            $comps = json_decode($this->request->getPost('menu_data'), true, 64);
            $this->update_position(0, $comps);

            $this->session->setFlashdata('message', 'Components saved.');

            return redirect()->to(admin_url('component'));
        }

        $this->template->set_meta_title('Components');
        $this->template->add_package(array('jquerynestable','sweetalert'),true);
        helper(['form']);

        $data['components'] = $this->getTree();

        $data['add_url'] = admin_url('component/add');
        $data['delete_url'] = admin_url('component/delete');

        return $this->template->view('Admin\Component\Views\index', $data);
    }

    public function add() {

        $json = [];

        if ($this->request->getMethod(1) === 'POST'){

            $compModel = new ComponentModel();

            $menu_id = $compModel->insert([
                'number' => $this->request->getPost('number'),
                'row_type' => $this->request->getPost('row_type'),
                'description' => $this->request->getPost('description'),
//                'description' => $this->request->getPost('description'),
            ]);

            $li = '<li class="dd-item" data-id="' . $menu_id . '">'
                . '<div class="dd-handle">';
            if($this->request->getPost('row_type')!='component'){
                $li .= '['.$this->request->getPost('number').'] '
                    . $this->request->getPost('description')
                    . '&nbsp;&nbsp;&nbsp; <a href="#" class="btn-remove">Remove</a>'.'</div>';
            } else {
                $li .= '['.$this->request->getPost('number').'] '
                    . $this->request->getPost('description')
                    . '&nbsp;&nbsp;&nbsp; <a href="#" class="btn-remove">Remove</a>'.'</div>';
            }

            $li .= '</li>';
            $json = [
                "li" => $li,
                "success" => "Component added Successfully",
            ];
        }
        return $this->response->setJSON($json);
    }

    public function delete() {

        $id = $this->request->getPost('id');

        $cModel = new ComponentModel();

        $cModel->delete($id);

        return $this->response->setJSON(['status'=>true]);

    }

    /**
     * Recursive function for save menu position
     */
    private function update_position($parent, $children) {
        $cModel = new ComponentModel();
        $i = 1;
        foreach ($children as $k => $v) {
            $menu_id = (int)$children[$k]['id'];
            $node_info_array = array();
            $node_info_array['parent'] =  $parent;
            $node_info_array['sort_order'] = $i;

            $cModel->update($menu_id,$node_info_array);
            if (isset($children[$k]['children'][0])) {
                $this->update_position($menu_id, $children[$k]['children']);
            }
            $i++;
        }
    }

}

<?php
namespace Admin\Localisation\Controllers;

use App\Controllers\AdminController;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\ClusterModel;
use Admin\Localisation\Models\DistrictModel;
use Admin\Localisation\Models\GrampanchayatModel;

class Block extends AdminController
{
    private $error = array();
    private $blockModel;

    public function __construct()
    {
        $this->blockModel = new BlockModel();
    }

    public function index()
    {
        $this->template->set_meta_title(lang('Block.heading_title'));
        return $this->getList();
    }

    public function add()
    {

        $this->template->set_meta_title(lang('Block.heading_title'));

        if ($this->request->getMethod(1) === 'POST' && $this->validateForm()) {

            //$code=$this->blockModel->getBlockCode($this->request->getPost());
            //$_POST['code']=$code;

            $id = $this->blockModel->insert($this->request->getPost());

            $this->session->setFlashdata('message', 'Block Saved Successfully.');

            return redirect()->to(base_url('admin/block'));
        }
        $this->getForm();
    }

    public function edit()
    {

        $this->template->set_meta_title(lang('Block.heading_title'));

        if ($this->request->getMethod(1) === 'POST' && $this->validateForm()) {
            $id = $this->uri->getSegment(4);

            $this->blockModel->update($id, $this->request->getPost());

            $this->session->setFlashdata('message', 'Block Updated Successfully.');

            return redirect()->to(base_url('admin/block'));
        }
        $this->getForm();
    }

    public function delete()
    {
        if ($this->request->getPost('selected')) {
            $selected = $this->request->getPost('selected');
        } else {
            $selected = (array)$this->uri->getSegment(4);
        }
        $this->blockModel->delete($selected);

        $this->session->setFlashdata('message', 'Block deleted Successfully.');
        return redirect()->to(base_url('admin/block'));
    }

    protected function getList()
    {

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => lang('Block.heading_title'),
            'href' => admin_url('block')
        );

        $this->template->add_package(array('datatable', 'select2'), true);

        $data['add'] = admin_url('block/add');
        $data['delete'] = admin_url('block/delete');
        $data['datatable_url'] = admin_url('block/search');

        $data['heading_title'] = lang('Block.heading_title');

        $data['text_list'] = lang('Block.text_list');
        $data['text_no_results'] = lang('Block.text_no_results');
        $data['text_confirm'] = lang('Block.text_confirm');

        $data['button_add'] = lang('Block.button_add');
        $data['button_edit'] = lang('Block.button_edit');
        $data['button_delete'] = lang('Block.button_delete');

        if (isset($this->error['warning'])) {
            $data['error'] = $this->error['warning'];
        }

        if ($this->request->getPost('selected')) {
            $data['selected'] = (array)$this->request->getPost('selected');
        } else {
            $data['selected'] = array();
        }

        $districtModel = new DistrictModel();
        $data['districts'] = $districtModel->orderBy('name')->findAll();


        return $this->template->view('Admin\Localisation\Views\block', $data);
    }

    public function search()
    {
        $requestData = $_REQUEST;
        $totalData = $this->blockModel->getTotals();
        $totalFiltered = $totalData;

        $filter_data = array(
            'filter_search' => $requestData['search']['value'],
            'filter_district' => $requestData['district'],
            'filter_block' => $requestData['block'],
            'order' => $requestData['order'][0]['dir'],
            'sort' => $requestData['order'][0]['column'],
            'start' => $requestData['start'],
            'limit' => $requestData['length']
        );
        $totalFiltered = $this->blockModel->getTotals($filter_data);

        $filteredData = $this->blockModel->getAll($filter_data);

        $datatable = array();
        foreach ($filteredData as $result) {

            $action = '<div class="btn-group btn-group-sm pull-right">';
            $action .= '<a class="btn btn-sm btn-primary" href="' . admin_url('block/edit/' . $result->id) . '"><i class="fa fa-pencil"></i></a>';
            $action .= '<a class="btn-sm btn btn-danger btn-remove" href="' . admin_url('block/delete/' . $result->id) . '" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
            $action .= '</div>';

            $datatable[] = array(
                '<input type="checkbox" name="selected[]" value="' . $result->id . '" />',
                $result->name,
                $result->district,
                $action
            );

        }
        //printr($datatable);
        $json_data = array(
            "draw" => isset($requestData['draw']) ? intval($requestData['draw']) : 1,
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $datatable
        );

        return $this->response->setContentType('application/json')
            ->setJSON($json_data);

    }

    protected function getForm()
    {

        $this->template->add_package(array('colorbox'), true);

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => lang('Block.heading_title'),
            'href' => admin_url('block')
        );

        //printr($_SESSION);
        $_SESSION['isLoggedIn'] = true;

        $data['heading_title'] = lang('Block.heading_title');
        $data['text_form'] = $this->uri->getSegment(3) ? "Block Edit" : "Block Add";
        $data['cancel'] = admin_url('block');

        if (isset($this->error['warning'])) {
            $data['error'] = $this->error['warning'];
        }

        if ($this->uri->getSegment(4) && ($this->request->getMethod(true) != 'POST')) {
            $block_info = $this->blockModel->find($this->uri->getSegment(4));
        }

        foreach ($this->blockModel->getFieldNames('soe_blocks') as $field) {
            if ($this->request->getPost($field)) {
                $data[$field] = $this->request->getPost($field);
            } else if (isset($block_info->{$field}) && $block_info->{$field}) {
                $data[$field] = html_entity_decode($block_info->{$field}, ENT_QUOTES, 'UTF-8');
            } else {
                $data[$field] = '';
            }
        }

        $districtModel = new DistrictModel();
        $data['districts'] = $districtModel->orderBy('name')->findAll();


        echo $this->template->view('Admin\Localisation\Views\blockForm', $data);
    }

    protected function validateForm()
    {
        //printr($_POST);
        $validation = \Config\Services::validation();
        $id = $this->uri->getSegment(4);
        $regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
        $regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
        $regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor

        $rules = $this->blockModel->validationRules;


        if ($this->validate($rules)) {
            return true;
        } else {
            //printr($validation->getErrors());
            $this->error['warning'] = "Warning: Please check the form carefully for errors!";
            return false;
        }
        return !$this->error;
    }

}

/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */
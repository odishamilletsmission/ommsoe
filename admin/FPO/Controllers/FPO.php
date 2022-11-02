<?php
namespace Admin\FPO\Controllers;
use App\Controllers\AdminController;
use App\Traits\TreeTrait;
use Config\Url;

class FPO extends AdminController
{
    use TreeTrait;
    public function index(){
        $data = [];
        $this->template->add_package(['datatable'],true);

        $data['upload_enabled'] = true;

        $data['years'] = getAllYears();
        $data['months'] = getAllMonths();

        $data['year_id'] = getCurrentYearId();
        $data['month_id'] = getCurrentMonthId();

        $data['datatable_url'] = Url::misDatatable;
        $data['add_url'] = Url::misAdd;

        return $this->template->view('Admin\MIS\Views\index', $data);
    }

    public function add() {
        $misModel = new MISModel();
        $misDetailModel = new MISDetailModel();
        $misFileModel = new MISFileModel();

        $block_id = $this->user->block_id;
        if($this->request->getGet('block_id')) {
            $block_id = $this->request->getGet('block_id');
        }
        $district_id = $this->user->district_id;
        if($this->request->getGet('district_id')) {
            $district_id = $this->request->getGet('district_id');
        }

        if($this->user->agency_type_id == $this->settings->district_user){
            $district_id = $this->user->district_id;
        }
        $agency_type_id = $this->user->agency_type_id;
        if($this->request->getGet('agency_type_id')) {
            $agency_type_id = $this->request->getGet('agency_type_id');
        }

        $month = $this->request->getGet('month');
        $year = $this->request->getGet('year');

        //validate if transaction exists
        $txn = $misModel->where([
            'block_id'=>$block_id,
            'district_id'=>$district_id,
            'agency_type_id'=>$agency_type_id,
            'month' => $month,
            'year' => $year,
            'user_id' => $this->user->user_id,
        ])->first();

        if($txn){
            $this->session->setFlashdata('message','Cannot add MIS. MIS already exists!!');
            return redirect()->to(Url::mis);
        }

        if($this->request->getMethod(1)=='POST'){

            $txn_data = [
                'block_id'=>$block_id,
                'district_id'=>$district_id,
                'agency_type_id'=>$agency_type_id,
                'month' => $month,
                'year' => $year,
                'status' => (int)in_array($this->user->agency_type_id,$this->settings->auto_approve_users),
                'user_id' => $this->user->user_id,
            ];
            $txn_id = $misModel->insert($txn_data);

            $achievements = [];

            foreach ($this->request->getPost('achievement') as $indicator_id => $value) {
                $achievements[] = [
                    'submission_id' => $txn_id,
                    'output_indicator_id' => $indicator_id,
                    'achievement' => isset($value['number'])?$value['number']:'',
                    'file' => isset($value['file'])?$value['file']:''
                ];
            }
            $misDetailModel->insertBatch($achievements);

            $this->session->setFlashdata('message','New MIS added');
            return redirect()->to(Url::mis);
        }



        return $this->getForm();

    }

    public function edit() {
//        $misModel = new MISModel();
        $misDetailModel = new MISDetailModel();
        $misFileModel = new MISFileModel();

        if($this->request->getMethod(1)=='POST'){

            $id = $this->uri->getSegment(4);

            $misDetailModel->where(['submission_id'=>$id])->delete();

            $achievements = [];

            foreach ($this->request->getPost('achievement') as $indicator_id => $value) {
                $achievements[] = [
                    'submission_id' => $id,
                    'output_indicator_id' => $indicator_id,
                    'achievement' => isset($value['number'])?$value['number']:'',
                    'file' => isset($value['file'])?$value['file']:''
                ];
            }
            $misDetailModel->insertBatch($achievements);

            $this->session->setFlashdata('message','MIS Updated Successfully');
            return redirect()->to(Url::mis);
        }



        return $this->getForm();

    }

    public function search() {
        if(!$this->request->isAJAX()){
            return $this->response->setStatusCode(404);
        }

        $misModel = new MISModel();
        $requestData = $_REQUEST;
        $totalData = $misModel->getTotal(['user_id'=>$this->user->user_id]);
        $totalFiltered = $totalData;

        $filter_search = $requestData['search']['value'];

        $order_columns = array(
            'block','month','year','date_added'
        );
        $filter_data = array(
            'user_id' => $this->user->user_id,
            'filter_search' => $filter_search,
            'order' => $requestData['order'][0]['dir'],
            'sort' => $order_columns[$requestData['order'][0]['column']],
            'start' => $requestData['start'],
            'limit' => $requestData['length']
        );
//        $totalFiltered = 0;
        $totalFiltered = $misModel->getTotal($filter_data);

        $filteredData = $misModel->getAll($filter_data);

        $datatable=array();

        foreach($filteredData as $result) {

            $action  = '<div class="btn-group">';
            $action .= '<a class="btn btn-sm btn-primary" href="' . Url::misEdit.'/'.$result->id . '"><i class="fa fa-pencil"></i></a>';
            $action .= '<a class="btn btn-sm btn-danger btn-delete" href="' . Url::misDelete.'/'.$result->id . '"><i class="fa fa-trash"></i></a>';
            $action .= '</div>';
            $status = '';
            if($result->status==0){
                $status = '<label class="badge badge-warning">'.$this->statuses[$result->status].'</label>';
            }
            if($result->status==1){
                $status = '<label class="badge badge-success">'.$this->statuses[$result->status].'</label>';
            }
            if($result->status==2){
                $status = '<label class="badge badge-danger">'.$this->statuses[$result->status].'</label>';
            }

            $datatable[]=array(
                $result->month,
                $result->year,
                ymdToDmy($result->created_at),
                $status,
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
        ob_end_clean();
        return $this->response->setJSON($json_data);

    }

    protected function getForm(){
        $data = [];
        $misModel = new MISModel();
        $compModel = new ComponentModel();
        $misDetailModel = new MISDetailModel();

        $this->template->add_package(['uploader','jquery_loading'],true);
        helper('form');

        $data['status'] = '-';
        $data['show_form'] = true;
        $txn = new \stdClass();
        if ($this->uri->getSegment(4) && ($this->request->getMethod(true) != 'POST')) {
            $txn = $misModel->find($this->uri->getSegment(4));

            if(!$txn){
                $this->session->setFlashdata('message','MIS not found!');
                return redirect()->to(Url::mis);
            }

            //validate user transaction
            if(!$misModel->canEdit($txn)){
                $this->session->setFlashdata('message','You cannot edit this MIS');
                return redirect()->to(Url::mis);
            }
            if($txn->status==0 || $txn->status==2){
                $action = 'edit';
                $data['show_form'] = true;
            } else {
                $action = 'view';
                $data['show_form'] = false;
            }

            //editing override for atma
            if($this->user->agency_type_id != $this->settings->block_user){
                $action = 'edit';
                $data['show_form'] = true;
            }

            $block_id = $txn->block_id;
            $district_id = $txn->district_id;
            $agency_type_id = $txn->agency_type_id;
            $month = $txn->month;
            $year = $txn->year;
            $data['status'] = $this->statuses[$txn->status];
            $misdetails = $misDetailModel->asArray()->where('submission_id', $this->uri->getSegment(4))->findAll();
        }else{
            $misdetails=[];
            $block_id = $this->user->block_id;
            if($this->request->getGet('block_id')) {
                $block_id = $this->request->getGet('block_id');
            }
            $district_id = $this->user->district_id;
            if($this->request->getGet('district_id')) {
                $district_id = $this->request->getGet('district_id');
            }

            if($this->user->agency_type_id == $this->settings->district_user){
                $district_id = $this->user->district_id;
            }
            $agency_type_id = $this->user->agency_type_id;
            if($this->request->getGet('agency_type_id')) {
                $agency_type_id = $this->request->getGet('agency_type_id');
            }

            $month = $this->request->getGet('month');
            $year = $this->request->getGet('year');
            $action="edit";
        }
        $filter = [
            'block_id' => $block_id,
            'month' => $month,
            'year' => $year,
            'user_group' => $agency_type_id
        ];

        if($district_id){
            $filter['district_id'] = $district_id;
        }

        $components = $compModel->getComponents($filter);

        $components = $this->buildTree($components,'parent','component_id');

        $data['components'] = $this->getTable($components,$misdetails,$action);

        $data['block'] = $block_id ? (new BlockModel)->find($block_id)->name:'-';
        $data['district'] = $district_id ? (new DistrictModel)->find($district_id)->name:'-';
        $data['agency_type'] = '-';
        if($agency_type_id){
            $data['agency_type'] = (new UserGroupModel)->find($agency_type_id)->name;
            if($data['agency_type']=='FA'){
                $data['agency_type'] = 'FA/CBO';
            }
        }

        $data['month'] = getMonthById($month)['name'];
        $data['year'] = getYear($year);
        $data['date_added'] = date('Y/m/d');

        $data['upload_url'] = Url::MISUpload;

        return $this->template->view('Admin\MIS\Views\form', $data);

    }

    public function getTable($array,$misdetails,$action="edit") {
        $misComponent_model = new MISComponentModel();

        $html = '';

        foreach ($array as $item) {
            if($item['row_type']=='heading') {
                $html .= '<tr class="heading">
                    <th>' . $item['number'] . '</th>
                    <th>' . $item['description'] . '</th>
                    <th colspan="4"></th>
                    </tr>
                ';
            } else {
                $inds = $misComponent_model->where([
                    'component_id'=>$item['component_id']
                ])->findAll();
                if($inds) {
                    $html .= '<tr data-parent="' . $item['parent'] . '">
                    <td rowspan="' . (count($inds) + 1) . '">' . $item['number'] . ' </td>
                    <td rowspan="' . (count($inds) + 1) . '">' . $item['description'] . ' </td>';
                    foreach ($inds as $indicator) {
                        $dkey= array_search($indicator->id, array_column($misdetails, 'output_indicator_id'));
                        if(isset($misdetails[$dkey])){
                            $achievement=$misdetails[$dkey]['achievement'];
                            $file=$misdetails[$dkey]['file'];
                            $file_url = '<a target="_blank" href="'.base_url('uploads/mis/'.$file).'">'.$file.'</a>';
                        }else{
                            $achievement='';
                            $file='';
                        }
                        $html .= '<tr title="'.$indicator->hint.'">';
                        $html .= '<td>' . $indicator->unit_type . ' </td>';
                        $html .= '<td>' . $indicator->output_indicator . ' </td>';
                        if($action=='edit'){
                            if($indicator->input_type=='number'){
                                $html .= '<td><input type="number" name="achievement['.$indicator->id.'][number]" value="'.$achievement.'" class="form-control" /></td>';
                            } else {
                                $html .= '<td class="dm-uploader">
                                    <div role="button" class="btn btn-outline-primary mr-2">
                                    <i class="si si-paper-clip"></i>
                                    <input type="file" title="">
                                    </div>
                                    <small class="status text-muted">'.($file?$file_url:'Upload file').'</small>
                                    <input type="hidden" id="file-'.$indicator->id.'" class="filepath" value="'.$file.'" name="achievement['.$indicator->id.'][file]">
                                    </td>';
                            }
                        } else {
                            if($indicator->input_type=='number'){
                                $html .= '<td>'.$achievement.'</td>';
                            } else {
                                $html .= '<td>'.($file?$file_url:'').'</td>';
                            }
                        }

                        $html .= '</tr>';
                    }
                }
            }
            if (!empty($item['children'])){
                $html .= $this->getTable($item['children'],$misdetails,$action);
            }
        }

        return $html;

    }

    public function upload() {
        $input = $this->validate([
            'file' => [
                'uploaded[file]',
                //'mime_in[file,application/pdf,]',
                'max_size[file,60000000]',
                'ext_in[file,xls,xlsx,pdf]',
            ]
        ]);

        if (!$input) {
            $data = [
                'status' => false,
                'message' => 'Invalid file',
                'errors' => $this->validator->getErrors()
            ];
        } else {
            $file = $this->request->getFile('file');
            $file->move(DIR_UPLOAD . 'mis');
            $data = [
                'status'=>true,
                'message'=> '<a target="_blank" href="'.base_url('uploads/mis/'.$file->getName()).'">'.$file->getName().'</a>',
                'filename' => $file->getName(),
                'filepath' => $file->getName()
            ];
        }
        return $this->response->setJSON($data);
    }

    public function isUploaded() {
        $data['mis_uploaded'] = false;

        $misModel = new MISModel();
        $txn = $misModel->where([
            'block_id' => $this->user->block_id,
            'district_id' => $this->user->district_id,
            'agency_type_id' => $this->user->agency_type_id,
            'month' => $this->request->getGet('month'),
            'year' => $this->request->getGet('year'),
            'user_id' => $this->user->user_id,
        ])->first();
        if($txn){
            $data['mis_uploaded'] = true;
        }

        return $this->response->setJSON($data);

    }
}

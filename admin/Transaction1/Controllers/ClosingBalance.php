<?php

namespace Admin\Transaction\Controllers;

use Admin\Common\Models\AllowuploadModel;
use Admin\Common\Models\MonthModel;
use Admin\Localisation\Models\BlockModel;
use Admin\Transaction\Models\ClosingbalanceModel;
use Admin\Users\Models\UserGroupModel;
use App\Controllers\AdminController;
use Config\Url;

class ClosingBalance extends AdminController {

    private $cbModel;

    public function __construct() {
        $this->cbModel = new ClosingbalanceModel();
        $this->monthModel = new MonthModel();
    }
	public function index() {

        $year_id = getCurrentYearId();
        if($this->request->getGet('year')){
            $year_id = $this->request->getGet('year');
        }

        $agency_type_id = $this->user->agency_type_id;
        if($this->request->getGet('agency_type_id')){
            $agency_type_id = $this->request->getGet('agency_type_id');
        }

        $fund_agency_id = $this->request->getGet('fund_agency_id');

        if($this->user->agency_type_id==$this->settings->block_user){
            $block = (new BlockModel())->find($this->user->block_id);
            $fund_agency_id = $block->fund_agency_id;
        }

        $upload_model = new AllowuploadModel();

        $date = date('Y-m-d');

        $upload = $upload_model->groupStart()->orWhere([
            'from_date >='=>$date,
            'to_date <='=>$date])->groupEnd()->where([
            'block_id'=>$this->user->block_id
        ])->find();
        $upload_enabled = $upload['enabled'];
        $allowed_month = $upload['month'];

        $filter = [
            'block_id' => $this->user->block_id,
            'year' => $year_id,
            'agency_type_id' => $agency_type_id,
            'fund_agency_id' => $fund_agency_id,
            'user_id' => $this->user->user_id,
        ];
        $data['months'] =  $this->cbModel->getLedgerReport($filter);

        foreach ($data['months'] as &$month) {
            $month->edit_url = '';
            if($month->month_id<=$allowed_month && $upload_enabled) {
                $month->edit_url = Url::closingBalanceEdit
                    . '/' . $month->year_id
                    . '/' . $month->month_id
                    . '/' . $month->agency_type_id
                    . '/' . $month->fund_agency_id;
            }
        }

        $data['year_id'] = $year_id;
        $data['agency_type_id'] = $agency_type_id;

        $data['message'] = $this->session->getFlashdata('message');

        $data['agency_types'] = [];
        if($this->user->agency_type_id==$this->settings->block_user) {
            $data['agency_types'] = (new UserGroupModel)->getBlockUsers();
        }

        $data['fund_agencies'] = [];
        if($this->user->agency_type_id!=$this->settings->block_user){
            if($this->user->district_id)
                $data['fund_agencies'] = (new BlockModel())->getFundAgencies(['district_id'=>$this->user->district_id]);
            else
                $data['fund_agencies'] = (new BlockModel())->getFundAgencies();
        }
        $data['fund_agency_id'] = $fund_agency_id;

        return $this->template->view('Admin\Transaction\Views\closingbalance', $data);
    }

    public function info($year,$month,$agency_type_id,$fund_agency_id) {

        $this->template->add_package(['uploader','jquery_loading'],true);
        helper('form');
        $data = [];

        $filter = [
            'block_id' => $this->user->block_id,
            'year' => $year,
            'agency_type_id' => $agency_type_id,
            'user_id' => $this->user->user_id
        ];

        $filter['month'] = $month;
        $cb = $this->cbModel->where($filter)->first();

        if($this->request->getMethod(1)=='POST'){

            //delete existing
            if ($cb){
                $this->cbModel->delete($cb->id);
            }

            //insert
            $data = [
                'user_id' => $this->user->getId(),
                'month' => $month,
                'year' => $year,
                'block_id' => $this->user->block_id,
                'district_id' => $this->user->district_id,
                'agency_type_id' => $agency_type_id,
                'status' => (int)in_array($this->user->agency_type_id,$this->settings->auto_approve_users)
            ];

            $data['advance'] = (float)$this->request->getPost('advance');
            $data['advance_file'] = $this->request->getPost('advance_file');
            $data['bank'] = (float)$this->request->getPost('bank');
            $data['bank_file'] = $this->request->getPost('bank_file');
            $data['cash'] = (float)$this->request->getPost('cash');
            $data['cash_file'] = $this->request->getPost('cash_file');

            $this->cbModel->save($data);

            return redirect()->to(Url::closingBalance)->with('message','Closing balance submitted for approval');
        }

        $filter['fund_agency_id'] = $fund_agency_id;
        $ledger =  $this->cbModel->getLedgerReport($filter,'array');

        $key = array_search($month,array_column($ledger,'month_id'));

        $data['summary'] = $ledger[$key];
        if($cb) {
            $data['summary']['status'] = $this->statuses[$cb->status];
        } else {
            $data['summary']['status'] = $this->statuses[3];
        }

        foreach($this->cbModel->getFieldNames() as $field){
            if($this->request->getPost($field)){
                $data[$field] = $this->request->getPost($field);
            } else if($cb){
                $data[$field] = $cb->{$field};
            } else {
                $data[$field] = '';
            }
        }
        $data['advance_file_url'] = '';
        if($cb && $cb->advance_file){
            $data['advance_file_url'] = anchor(base_url('uploads/cb/'.$cb->advance_file),$cb->advance_file,'target="_blank"');
        }
        $data['bank_file_url'] = '';
        if($cb && $cb->bank_file){
            $data['bank_file_url'] = anchor(base_url('uploads/cb/'.$cb->bank_file),$cb->bank_file,'target="_blank"');
        }
        $data['cash_file_url'] = '';
        if($cb && $cb->cash_file){
            $data['cash_file_url'] = anchor(base_url('uploads/cb/'.$cb->cash_file),$cb->cash_file,'target="_blank"');
        }

        $data['upload_url'] = Url::closingBalanceUpload;

        return $this->template->view('Admin\Transaction\Views\closingbalance_form', $data);
    }

    public function upload(){

        $input = $this->validate([
            'file' => [
                'uploaded[file]',
                'mime_in[file,image/jpg,image/jpeg,image/png,image/JPG,image/JPEG,image/PNG]',
                'max_size[file,1024]',
                'ext_in[file,jpg,png,jpeg,JPG,PNG,JPEG]',
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
            $file->move(DIR_UPLOAD . 'cb');
            $data = [
                'status'=>true,
                'message'=> '<a target="_blank" href="'.base_url('uploads/cb/'.$file->getName()).'">'.$file->getName().'</a>',
                'filename' => $file->getName(),
                'filepath' => $file->getName()
            ];
        }
        return $this->response->setJSON($data);
    }
}

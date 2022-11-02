<?php

namespace Admin\Transaction\Controllers;

use Admin\Common\Models\AllowuploadModel;
use Admin\Common\Models\MonthModel;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use Admin\Transaction\Models\ClosingbalanceModel;
use Admin\Users\Models\UserGroupModel;
use Admin\Users\Models\UserModel;
use App\Controllers\AdminController;
use Config\Url;

class ClosingBalance extends AdminController {

    private $cbModel;

    public function __construct() {
        $this->cbModel = new ClosingbalanceModel();
        $this->monthModel = new MonthModel();
    }

	public function index() {

        if($this->user->agency_type_id==$this->settings->ps_user){
            return $this->agency();
        }
        if($this->user->agency_type_id==$this->settings->rs_user){
            return $this->agency();
        }

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
        if($this->user->agency_type_id==$this->settings->district_user){
            $user = (new UserModel())->find($this->user->id);
            $fund_agency_id = $user->fund_agency_id;
        }

        $upload_model = new AllowuploadModel();

        if($this->user->agency_type_id==$this->settings->block_user) {
            $ufilter = [
                'block_id' => $this->user->block_id,
                'agency_type_id' => $this->user->agency_type_id
            ];
        }
        if($this->user->agency_type_id==$this->settings->district_user){
            $ufilter = [
                'district_id' => $this->user->district_id,
                'agency_type_id' => $this->user->agency_type_id
            ];
        }

        $upload = $upload_model->getByDate($ufilter);

        $upload_enabled = (bool)$upload;
        $allowed_month = $upload_enabled ? $upload['month']:0;

        $filter = [
            'year' => $year_id,
            'agency_type_id' => $agency_type_id,
            'fund_agency_id' => $fund_agency_id,
            'user_id' => $this->user->user_id,
        ];
        if($this->user->agency_type_id==$this->settings->block_user || $this->user->agency_type_id==$this->settings->cbo_user) {
            $filter['block_id'] = $this->user->block_id;
        }
        if($this->user->agency_type_id==$this->settings->district_user) {
            $filter['district_id'] = $this->user->district_id;
        }
        $data['months'] =  $this->cbModel->getLedgerReport($filter);

        foreach ($data['months'] as &$month) {
            $status = $month->status;
            if($month->status==0){
                $month->status = '<label class="badge badge-warning">'.$this->statuses[$month->status].'</label>';
            }
            if($month->status==1){
                $month->status = '<label class="badge badge-success">'.$this->statuses[$month->status].'</label>';
            }
            if($month->status==2){
                $month->status = '<label class="badge badge-danger">'.$this->statuses[$month->status].'</label>';
            }
            if($month->status==3){
                $month->status = '<label class="badge badge-info">'.$this->statuses[$month->status].'</label>';
            }
            $month->edit_url = '';
            if(($month->month_id==$allowed_month) || ($status==0 || $status==2)) {
                $month->edit_url = Url::closingBalanceEdit
                    . '/' . $month->year_id
                    . '/' . $month->month_id
                    . '/' . $month->agency_type_id
                    . '/' . $fund_agency_id;
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
            'year' => $year,
            'agency_type_id' => $agency_type_id,
            'user_id' => $this->user->user_id
        ];
        if($this->user->agency_type_id==$this->settings->block_user || $this->user->agency_type_id==$this->settings->cbo_user) {
            $filter['block_id'] = $this->user->block_id;
        }
        if($this->user->agency_type_id==$this->settings->district_user) {
            $filter['district_id'] = $this->user->district_id;
        }

        //temporary skip validation
        $data['skip_file_validation'] = false;
        if($year=1 && in_array($month,range(1,9))){
            $data['skip_file_validation'] = true;
        }


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
                'mime_in[file,image/jpg,image/jpeg,image/png,image/JPG,image/JPEG,image/PNG,application/pdf,application/PDF,application/x-pdf]',
                'max_size[file,1024]',
                'ext_in[file,jpg,png,jpeg,JPG,PNG,JPEG,pdf,PDF]',
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

    public function agency() {
        $year_id = getCurrentYearId();
        if($this->request->getGet('year')){
            $year_id = $this->request->getGet('year');
        }
        $month_id = getMonthIdByMonth(date('m'));
        if($this->request->getGet('month')){
            $month_id = $this->request->getGet('month');
        }

        $cbModel = new ClosingbalanceModel();

        $cb = $cbModel->where([
            'year' => $year_id,
            'month' => $month_id,
            'user_id' => $this->user->user_id,
        ])->first();

        if($this->request->getMethod(1)=='POST'){

            //delete existing
            if ($cb){
                $cbModel->delete($cb->id);
            }

            //insert
            $data = [
                'user_id' => $this->user->user_id,
                'month' => $month_id,
                'year' => $year_id,
                'block_id' => 0,
                'district_id' => $this->user->district_id,
                'agency_type_id' => $this->user->agency_type_id,
                'status' => (int)in_array($this->user->agency_type_id,$this->settings->auto_approve_users)
            ];

            $data['advance'] = (float)$this->request->getPost('advance');
            $data['bank'] = (float)$this->request->getPost('bank');
            $data['cash'] = (float)$this->request->getPost('cash');

            $this->cbModel->save($data);

            return redirect()->to(Url::closingBalance)->with('message','Closing balance submitted for approval');
        }

        $filter = [
            'district_id' => $this->user->district_id,
//            'agency_type_id' => $this->user->agency_type_id,
            'year' => $year_id,
            'month' => $month_id,
            'user_id' => $this->user->user_id,
        ];
        $filter = array_filter($filter);

        $ob = $cbModel->getOpeningBalanceByMonth($filter);

        $data['summary'] = [
            'year' => getYearByYearID($year_id),
            'month' => getMonthById($month_id)['name'],
            'agency_type' => (new UserGroupModel())->find($this->user->agency_type_id)->name,
            'ob' => $ob->ob_total + $ob->fr_upto_total + $ob->otr_upto_total - $ob->ex_upto_total,
            'fr' => $ob->fr_total,
            'mt' => $ob->otr_total,
            'exp' => $ob->ex_total,
            'bal' => ($ob->ob_total + $ob->fr_upto_total + $ob->otr_upto_total - $ob->ex_upto_total) + $ob->fr_total + $ob->otr_total - $ob->ex_total,
            'status' => $cb ? $this->statuses[$cb->status]:$this->statuses[3],
        ];

        $data['advance'] = '';
        $data['bank'] = '';
        $data['cash'] = '';
        $data['status'] = '';
        if($cb) {
            $data['advance'] = $cb->advance;
            $data['bank'] = $cb->bank;
            $data['cash'] = $cb->cash;
            $data['status'] = $cb->status;
        }

        $data['fund_agencies'] = [];
        $data['year_id'] = $year_id;
        $data['month_id'] = $month_id;

        $data['years'] = getAllYears();
        $data['months'] = getAllMonths();

        return $this->template->view('Admin\Transaction\Views\closingbalance_district', $data);
    }
}

<?php
namespace Admin\Common\Controllers;

use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use Admin\Reports\Controllers\Reports;
use Admin\Reports\Models\ReportsModel;
use Admin\Transaction\Controllers\OpeningBalance;
use Admin\Transaction\Models\ClosingbalanceModel;
use Admin\Transaction\Models\FRCheckModel;
use App\Controllers\AdminController;
use App\Traits\ReportTrait;
use App\Traits\TreeTrait;
use CodeIgniter\Config\View;
use Config\Url;

class Dashboard extends AdminController
{
    use TreeTrait,ReportTrait {
        ReportTrait::generateTable insteadof TreeTrait;
        ReportTrait::getTable insteadof TreeTrait;
    }

	public function index() {
	    $data = [];

	    if(!$this->request->isAJAX()){
            $data['fr_check'] = $this->fund_receipt_check();
        } else {
	        return $this->fund_receipt_check();
        }

        $data['fr_url'] = site_url(Url::transactionAdd) . '?month='.getCurrentMonthId().'&year='.getCurrentYearId().'&txn_type=fund_receipt&agency_type_id='.$this->user->agency_type_id;

	    $data['year'] = date('F').' '.getYear(getCurrentYearId());

        if($this->user->agency_type_id==$this->settings->block_user){
            return $this->fa_dashboard($data);
        }

        if($this->user->agency_type_id==$this->settings->district_user){
            return $this->atma_dashboard($data);
        }

        if($this->user->agency_type_id==$this->settings->ps_user){
            return $this->ps_dashboard($data);
        }

        if($this->user->agency_type_id==$this->settings->spmu_user){
            return $this->spmu_dashboard($data);
        }

        return $this->template->view('Admin\Common\Views\dashboard',$data);
	}

	protected function fa_dashboard(&$data){
        $reportModel = new ReportsModel();

        $filter = [
            'user_id' => $this->user->user_id,
            'block_id' => $this->user->block_id,
            'year_upto' => getCurrentYearId(),
        ];

//        $data['ob'] = $reportModel->getOpeningBalanceTotal($filter);

        $filter['transaction_type'] = 'fund_receipt';
        $data['fr'] = $reportModel->getTransactionTotal($filter);

        $filter['transaction_type'] = 'expense';
        $data['ex'] = $reportModel->getTransactionTotal($filter);

        $filter['month'] = getMonthIdByMonth(date('m'));
        $filter['year'] = getCurrentYearId();

        $data['cb'] = $reportModel->getClosingBalanceTotal($filter);

        $reportModel = new ReportsModel();
        $data['components'] = [];
        $filter = [
            'user_id' => $this->user->user_id,
            'block_id' => $this->user->block_id,
            'month_id' => getMonthIdByMonth(date('m')),
            'year_id' => getCurrentYearId(),
            'agency_type_id' => [5]
        ];
        $filter['block_users'] = [5,6];
        $filter['block_user'] = false;
        if($this->user->agency_type_id==$this->settings->block_user){
            $filter['block_user'] = true;
        }

        $components = $reportModel->getMpr($filter);
        $components = $this->buildTree($components,'parent','component_id');

        $data['components'] = $this->getTable($components,'view');

        return $this->template->view('Admin\Common\Views\fa_dashboard',$data);

    }

	protected function ps_dashboard(&$data){
        $reportModel = new ReportsModel();

        $filter = [
            'user_id' => $this->user->user_id,
            'year_upto' => getCurrentYearId(),
        ];

//        $data['ob'] = $reportModel->getOpeningBalanceTotal($filter);

        $filter['transaction_type'] = 'fund_receipt';
        $data['fr'] = $reportModel->getTransactionTotal($filter);

        $filter['transaction_type'] = 'expense';
        $data['ex'] = $reportModel->getTransactionTotal($filter);

        $filter['month'] = getMonthIdByMonth(date('m'));
        $filter['year'] = getCurrentYearId();

        $data['cb'] = $reportModel->getClosingBalanceTotal($filter);

        $reportModel = new ReportsModel();
        $data['components'] = [];
        $filter = [
            'user_id' => $this->user->user_id,
            'month_id' => getMonthIdByMonth(date('m')),
            'year_id' => getCurrentYearId(),
            'user_group' => [$this->settings->ps_user]
        ];
        $filter['block_users'] = [5,6];
        $filter['block_user'] = false;
        if($this->user->agency_type_id==$this->settings->block_user){
            $filter['block_user'] = true;
        }
        $components = $reportModel->getMpr($filter);
        $components = $this->buildTree($components,'parent','component_id');

        $data['components'] = $this->getTable($components,'view');

        return $this->template->view('Admin\Common\Views\ps_dashboard',$data);

    }

	protected function atma_dashboard(&$data){
	    $month = $this->request->getGet('month');
	    $year = $this->request->getGet('year');
        $reportModel = new ReportsModel();

        $filter = [
            'user_id' => $this->user->user_id,
            'district_id' => $this->user->district_id,
            'year_upto' => getCurrentYearId(),
        ];

//        $data['ob'] = $reportModel->getOpeningBalanceTotal($filter);

        $filter['transaction_type'] = 'fund_receipt';
        $data['fr'] = $reportModel->getTransactionTotal($filter);

        /*
        $filter['user_id'] = null;
        $filter['district_id'] = null;
        $block_id = (new BlockModel())->select('id')
            ->where(['district_id'=>$this->user->district_id])->findAll();
        $block_ids = [];
        foreach ($block_id as $item) {
            $block_ids[] = $item->id;
        }
        $data['frel'] = $reportModel->getTransactionTotal($filter);
        */

        $filter['user_id'] = $this->user->user_id;
        $filter['district_id'] = $this->user->district_id;
        $filter['transaction_type'] = 'expense';
        $data['ex'] = $reportModel->getTransactionTotal($filter);

        $filter['month'] = getMonthIdByMonth(date('m'));
        $filter['year'] = getCurrentYearId();

        $data['cb'] = $reportModel->getClosingBalanceTotal($filter);

        $data['components'] = [];
        $filter = [
            'user_id' => $this->user->user_id,
            'district_id' => $this->user->district_id,
            'month_id' => getMonthIdByMonth(date('m')),
            'year_id' => getCurrentYearId(),
            'agency_type_id' => [5,6,7]
        ];
        $filter['block_users'] = [5,6];
        $filter['block_user'] = false;
        if($this->user->agency_type_id==$this->settings->block_user){
            $filter['block_user'] = true;
        }
        $reportModel = new ReportsModel();

        $components = $reportModel->getMpr($filter);
        $components = $this->buildTree($components,'parent','component_id');

        $data['components'] = $this->getTable($components,'view');

        $data['upload_status'] = $this->upload_status([
            'district_id'=>$this->user->district_id
        ]);

        return $this->template->view('Admin\Common\Views\atma_dashboard',$data);

    }

	protected function spmu_dashboard(&$data){
        $reportModel = new ReportsModel();

        $filter = [
//            'user_id' => $this->user->user_id,
//            'district_id' => $this->user->district_id,
            'agency_type_id' => [7,8,9],
            'fund_agency_id' => 1,
            'year_upto' => getCurrentYearId(),
        ];

        $filter['transaction_type'] = 'fund_receipt';
        $data['fr'] = $reportModel->getTransactionTotal($filter);

        $filter['transaction_type'] = 'expense';
        $filter['agency_type_id'] = [5,6,7,8,9];
        $data['ex'] = $reportModel->getTransactionTotal($filter);

        //$filter['month'] = getMonthIdByMonth(date('m'));
        //$filter['year'] = getCurrentYearId();

//        $data['cb'] = $reportModel->getClosingBalanceTotal($filter);

        $data['cb'] = $data['fr'] - $data['ex'];

        //$data['districts'] = (new DistrictModel())->asArray()->findAll();
//        $data['district_id'] = '0';
//        if($this->request->getGet('district_id')){
//            $data['district_id'] = $this->request->getGet('district_id');
//        }
//        $data['upload_status'] = $this->upload_status($data);

        return $this->template->view('Admin\Common\Views\spmu_dashboard',$data);

    }

    protected function fund_receipt_check(){

        $frcModel = new FRCheckModel();

	    if($this->request->isAJAX()){
	        $choice = $this->request->getGet('choice');

	        $data = [
	            'month' => getCurrentMonthId(),
                'year' => getCurrentYearId(),
                'district_id' => $this->user->district_id,
                'block_id' => $this->user->block_id
            ];
	        if($choice=='yes'){
	            $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }

            $frcModel->insert($data);

            return $this->response->setJSON(['success'=>true]);

        }

	    $where = [
	        'block_id' => $this->user->block_id,
	        'district_id' => $this->user->district_id,
            'month' => getCurrentMonthId(),
            'year' => getCurrentYearId(),
        ];

	    $fr = $frcModel->where($where)->first();

        return !$fr;
	}

	protected function upload_status($filter=[]){

        $data = $this->getUploadStatus($filter);

        return view('Admin\Reports\Views\upload_status',$data);
    }
}

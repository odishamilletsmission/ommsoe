<?php 
namespace Admin\Reports\Controllers;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use Admin\Reports\Models\ReportsModel;
use Admin\Transaction\Models\ClosingbalanceModel;
use Admin\Transaction\Models\MisctransactionModel;
use Admin\Users\Models\UserGroupModel;
use Admin\Users\Models\UserModel;
use App\Controllers\AdminController;

class SFP extends AdminController
{
    public function index() {
        $data = [];

        $data['year_id'] = getCurrentYearId();
        if($this->request->getGet('year')){
            $data['year_id'] = $this->request->getGet('year');
        }

        $data['month_id'] = getMonthIdByMonth(date('m'));
        if($this->request->getGet('month')){
            $data['month_id'] = $this->request->getGet('month');
        }

        $data['agency_type_id'] = '';
        if($this->request->getGet('agency_type_id')){
            $data['agency_type_id'] = $this->request->getGet('agency_type_id');
        }

        $cbModel = new ClosingbalanceModel();

        $data['opening'] = [];

        $filter = [
            'block_id' => $this->user->block_id,
            'year' => $data['year_id'],
            'month' => $data['month_id'],
            'user_id' => $this->user->user_id
        ];

        if($data['agency_type_id']){
            $filter['agency_type_id'] = $data['agency_type_id'];
        } 

        //ob
        $obs = $cbModel->getOpeningBalanceBreakup($filter);

        $data['opening']['advance'] = 0;
        $data['opening']['bank'] = 0;
        $data['opening']['cash'] = 0;
        $data['opening']['total'] = 0;

        foreach ($obs as $ob) {
            $data['opening']['advance'] += $ob->advance;
            $data['opening']['bank'] += $ob->bank;
            $data['opening']['cash'] += $ob->cash;

            $data['opening']['total'] += ($ob->advance+$ob->bank+$ob->cash);
        }

        //fr & exp
        $reportModel = new ReportsModel();
        $filter['transaction_type'] = 'fund_receipt';
        $data['fund_receipt'] = (float)$reportModel->getTransactionTotal($filter);

        $filter['transaction_type'] = 'expense';
        $data['expense'] = (float)$reportModel->getTransactionTotal($filter);;

        //or
        $orModel = new MisctransactionModel();
        $ors = $orModel->getHeadwiseAmount($filter);

        $or_total = 0;
        $data['or'] = [];
        foreach ($ors as $or) {
            $data['or'][] = [
                'head' => $or->head,
                'total' => $or->total
            ];
            $or_total += ($or->sign*$or->total);
        }
        $data['or_total'] = $or_total;

        //cb
        $cbModel = new ClosingbalanceModel();
        unset($filter['transaction_type']);
        $cbs = $cbModel->where($filter)->findAll();

        $data['closing']['advance'] = 0;
        $data['closing']['bank'] = 0;
        $data['closing']['cash'] = 0;
        $data['closing']['total'] = 0;

        foreach ($cbs as $ob) {
            $data['closing']['advance'] += $ob->advance;
            $data['closing']['bank'] += $ob->bank;
            $data['closing']['cash'] += $ob->cash;

            $data['closing']['total'] += ($ob->advance+$ob->bank+$ob->cash);
        }

        $this->filterPanel($data);

        return $this->template->view('Admin\Reports\Views\sfp', $data);
    }

    protected function filterPanel(&$data){
        $data['years'] = getAllYears();
        $data['months'] = getAllMonths();

        $data['agency_types'] = [];
        foreach ($this->settings->user_can_access as $user_group => $user_can_access_grp) {
            if ($this->user->agency_type_id == $user_group) {
                $data['agency_types'] = (new UserGroupModel())->whereIn('id',
                    $user_can_access_grp)->orderBy('name')->asArray()->findAll();
            }
        }

        $month = getMonthById($data['month_id']);

        $data['reporting_period'] = date('d F',strtotime('last day of '.$month['name'])).' '.getYear($data['year_id']);
        $data['agency_name'] = '';
        $data['report_date'] = date('d/m/Y');

        if($this->user->agency_type_id==$this->settings->block_user) {
            $data['agency_type_name'] = 'FA & CBO';
        }
        if($this->user->agency_type_id==$this->settings->district_user) {
            $data['agency_type_name'] = 'ATMA';
        }

        $userModel  = new UserModel();

        if($data['agency_type_id']) {
            $data['agency_type_name'] = (new UserGroupModel())->where(['id' => $data['agency_type_id']])->first()->name;
        }
        $data['block_name'] = '-';
        $data['district_name'] = '-';
        $user = null;
        if($this->user->agency_type_id==$this->settings->block_user){
            $data['block_name'] = (new BlockModel())->where(['id'=>$this->user->block_id])->first()->name;
            $data['district_name'] = (new DistrictModel())->where(['id'=>$this->user->district_id])->first()->name;
            $user = $userModel->where(['user_group_id'=>$data['agency_type_id'],'block_id'=>$this->user->block_id])->first();
        }
        if($this->user->agency_type_id==$this->settings->district_user){
            $data['district_name'] = (new DistrictModel())->where(['id'=>$this->user->district_id])->first()->name;
            $data['agency_types'] = [];
            $user = $userModel->where(['user_group_id'=>$data['agency_type_id'],'district_id'=>$this->user->district_id])->first();
        }

        if($user){
            $data['agency_name'] = $user->firstname;
        }
    }
}

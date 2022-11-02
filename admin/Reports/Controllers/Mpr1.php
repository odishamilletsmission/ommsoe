<?php 
namespace Admin\Reports\Controllers;
use Admin\Common\Models\CommonModel;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use Admin\Reports\Models\ReportsModel;
use Admin\Transaction\Models\TransactionModel;
use Admin\Users\Models\UserGroupModel;
use Admin\Users\Models\UserModel;
use App\Controllers\AdminController;
use App\Traits\ReportTrait;
use App\Traits\TreeTrait;
use Config\Url;

class Mpr extends AdminController
{
    use TreeTrait,ReportTrait {
        ReportTrait::generateTable insteadof TreeTrait;
        ReportTrait::getTable insteadof TreeTrait;
    }

    public function index($action='') {
        $data = [];
//        $txnModel = new TransactionModel();

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

        $data['district_id'] = '';
        if($this->request->getGet('district_id')){
            $data['district_id'] = $this->request->getGet('district_id');
        }

        $data['block_id'] = '';
        if($this->request->getGet('block_id')){
            $data['block_id'] = $this->request->getGet('block_id');
        }

        if($this->user->agency_type_id==$this->settings->block_user){
            $data['block_id'] = $this->user->block_id;
        }

        if($this->user->agency_type_id==$this->settings->district_user){
            $data['district_id'] = $this->user->district_id;
        }

        if($data['block_id']){
            $where = ['block_id'=>$data['block_id'],'user_group_id'=>$this->settings->block_user];
            $user = (new UserModel())->where($where)->first();
        }

        $data['agency_types'] = [];
        foreach ($this->settings->user_can_access as $user_group => $user_can_access_grp) {
            if(5==$user_group){
                $data['agency_types'] = (new UserGroupModel())->whereIn('id',
                    $user_can_access_grp)->orderBy('name')->asArray()->findAll();
            }
        }

        $data['districts'] = [];

        $this->block_model = new BlockModel();

        $data['blocks'] = [];
        $data['fund_agencies'] = [];

        $reportModel = new ReportsModel();
        $data['components'] = [];
        $filter = [
            'month_id' => $data['month_id'],
            'year_id' => $data['year_id'],
        ];
        if(isset($user)){
            $filter['user_id'] = $user->id;
        }
        if($data['district_id']){
            $filter['district_id'] = $data['district_id'];
        }
        if($data['block_id']){
            $filter['block_id'] = $data['block_id'];
            $filter['user_group'] = $this->settings->block_user;
        }
        if($data['agency_type_id']){
            $filter['agency_type_id'] = $data['agency_type_id'];
        }

        $components = $reportModel->getMpr($filter);

        $components = $this->buildTree($components, 'parent', 'component_id');


        $data['components'] = $this->getTable($components, 'view');

        if($data['district_id']) {
            $data['district'] = (new DistrictModel())->find($data['district_id'])->name;
        }
        $data['month_name'] = getMonthById($data['month_id'])['name'];
        $data['fin_year'] = getYear($data['year_id']);

        if($data['block_id']) {
            $block = $this->block_model->find($data['block_id']);
            $data['block'] = $block->name;

            $data['fund_agency'] = $block->fund_agency_id ? (new CommonModel())->getFundAgency($block->fund_agency_id)['name']:'-';
        }

        $this->filterPanel($data);
//dd($data);
        if($action=='download'){
            $this->download($data,$components);
            exit;
        }

        $data['download_url'] = Url::mprDownload.'?year='.$data['year_id'].'&month='.$data['month_id'].'&agency_type_id='.$data['agency_type_id'];


        return $this->template->view('Admin\Reports\Views\mpr_block', $data);
    }

    protected function filterPanel(&$data) {
        $data['years'] = getAllYears();
        $data['months'] = getAllMonths();
        $data['filter_panel'] = '';
        if($this->user->agency_type_id==$this->settings->district_user) {
            $data['blocks'] = $this->block_model->where(['district_id' => $this->user->district_id])->asArray()->findAll();
            $data['filter_panel'] = view('Admin\Reports\Views\district_filter_panel',$data);
        }
        if($this->user->agency_type_id==$this->settings->block_user){
            $data['filter_panel'] = view('Admin\Reports\Views\block_filter_panel',$data);
        }
        if($this->user->agency_type_id==$this->settings->ps_user){
            $data['districts'] = (new DistrictModel())->asArray()->findAll();
            $data['blocks'] = $this->block_model->where(['district_id' => $this->request->getGet('district_id')])->asArray()->findAll();
            foreach ($this->settings->user_can_access as $user_group => $user_can_access_grp) {
                if(8==$user_group){
                    $data['agency_types'] = (new UserGroupModel())->whereIn('id',
                        $user_can_access_grp)->orderBy('name')->asArray()->findAll();
                }
            }

            $data['filter_panel'] = view('Admin\Reports\Views\state_filter_panel',$data);
        }
        if($this->user->agency_type_id==11){
            $data['districts'] = (new DistrictModel())->asArray()->findAll();
            $data['blocks'] = $this->block_model->where(['district_id' => $this->request->getGet('district_id')])->asArray()->findAll();
            foreach ($this->settings->user_can_access as $user_group => $user_can_access_grp) {
                if(8==$user_group){
                    $data['agency_types'] = (new UserGroupModel())->whereIn('id',
                        $user_can_access_grp)->orderBy('name')->asArray()->findAll();
                }
            }

            $data['filter_panel'] = view('Admin\Reports\Views\state_filter_panel',$data);
        }
    }

}

<?php 
namespace Admin\Reports\Controllers;
use Admin\Common\Models\CommonModel;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use Admin\Reports\Models\MPRUploadModel;
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

        $data['fund_agency_id'] = 1;
        if($this->user->fund_agency_id){
            $data['fund_agency_id'] = $this->user->fund_agency_id;
        }
        if($this->request->getGet('fund_agency_id')){
            $data['fund_agency_id'] = $this->request->getGet('fund_agency_id');
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
            $data['block_user_id'] = $user->id;
        }

        if($data['district_id']){
            $where = ['district_id'=>$data['district_id'],'user_group_id'=>$this->settings->district_user];
            $user = (new UserModel())->where($where)->first();
            $data['district_user_id'] = $user->id;
        }

        $data['agency_types'] = [];
        // only ps and rs in state login where user dont belongs to any district
        if(!$this->user->district_id) {
            $data['agency_types'] = (new UserGroupModel())->whereIn('id', [8, 9])
                ->orderBy('name')->asArray()->findAll();
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
            'user_id' => $this->user->id,
        ];

        if($data['agency_type_id']){
            $filter['agency_type_id'] = $data['agency_type_id'];
        }
        if($data['district_id']){
            $filter['district_id'] = $data['district_id'];
            $filter['agency_type_id'] = [5,6,7]; //fa/cbo/atma --to be added to settings
        }
        if($data['block_id']){
            $filter['block_id'] = $data['block_id'];
            $filter['block_user_id'] = $data['block_user_id'];
            $filter['agency_type_id'] = [5,6,7]; //fa/cbo --to be added to settings
            $filter['category'] = 'program'; //only program components when block is selected
        }
        if($data['fund_agency_id']){
            $filter['fund_agency_id'] = $data['fund_agency_id'];
            $data['districts'] = (new DistrictModel())->getDistrictsByFundAgency($data['fund_agency_id']);
        }

        $filter['block_users'] = [5,6];
        $filter['block_user'] = false;
        if($this->user->agency_type_id==$this->settings->block_user){
            $filter['block_user'] = true;
        }
        if($this->user->agency_type_id==$this->settings->district_user){
            $filter['district_user_id'] = $data['district_user_id'];
        }

        $components = $reportModel->getMpr($filter);

        $components = $this->buildTree($components, 'parent', 'component_id');

        $data['components'] = $this->getTable($components, 'view');

        if($data['district_id']) {
            $data['district'] = (new DistrictModel())->find($data['district_id'])->name;
            $data['blocks'] = $this->block_model->where(
                [
                    'district_id' => $data['district_id'],
                    'fund_agency_id' => $data['fund_agency_id'],
                ]
            )->asArray()->findAll();

        }

        if($data['block_id']) {
            $block = $this->block_model->find($data['block_id']);
            $data['block'] = $block->name;

            $data['fund_agency'] = $block->fund_agency_id ? (new CommonModel())->getFundAgency($block->fund_agency_id)['name']:'-';
        }
        $data['month_name'] = getMonthById($data['month_id'])['name'];
        $data['fin_year'] = getYear($data['year_id']);

        $this->filterPanel($data);

        if($action=='download'){
            $this->download($data,$components);
            exit;
        }

        $data['download_url'] = Url::mprDownload.'?year='.$data['year_id'].'&month='.$data['month_id'].'&agency_type_id='.$data['agency_type_id'];

        return $this->template->view('Admin\Reports\Views\mpr_block', $data);
    }

    //not required
    public function abstractBudget($action='') {
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

        $data['fund_agency_id'] = '';
        if($this->request->getGet('fund_agency_id')){
            $data['fund_agency_id'] = $this->request->getGet('fund_agency_id');
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
        // only ps and rs in state login where user dont belongs to any district
        if(!$this->user->district_id) {
            $data['agency_types'] = (new UserGroupModel())->whereIn('id', [8, 9])
                ->orderBy('name')->asArray()->findAll();
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
        if($data['agency_type_id']){
            $filter['agency_type_id'] = $data['agency_type_id'];
        }
        if($data['district_id']){
            $filter['district_id'] = $data['district_id'];
            $filter['agency_type_id'] = [5,6,7]; //fa/cbo/atma --to be added to settings
        }
        if($data['block_id']){
            $filter['block_id'] = $data['block_id'];
            $filter['agency_type_id'] = [5,6,7]; //fa/cbo --to be added to settings
            $filter['category'] = 'program'; //only program components when block is selected
        }
        if($data['fund_agency_id']){
            $filter['fund_agency_id'] = $data['fund_agency_id'];
            $data['districts'] = (new DistrictModel())->getDistrictsByFundAgency($data['fund_agency_id']);
        }

        $components = $reportModel->getAbstractMpr($filter);
//dd($components);
        $program_heads = [
            'program' => 'Programme Cost',
            'pmu' => 'PMU Cost',
            'addl' => 'Additional Budget - New Components',
        ];

        $slno = 1;
        foreach ($program_heads as $key => $head) {
            $key = array_search('program',array_column($components,'block_category'));

            $data['components'][] = [
                'slno' => $slno,
                'head' => $head,
                ''
            ];

            $slno++;
        }
        $program_heads = [
            'procurement' => 'Procurement of Ragi by TDCCOL (6.00 lakh qntl.)',
            'pds' => 'Distribution under PDS & others',
        ];


//        $components = $this->buildTree($components, 'parent', 'component_id');

//        $data['components'] = $this->getTable($components, 'view');

        //$components


        if($data['district_id']) {
            $data['district'] = (new DistrictModel())->find($data['district_id'])->name;
            $data['blocks'] = $this->block_model->where(
                [
                    'district_id' => $data['district_id'],
                    'fund_agency_id' => $data['fund_agency_id'],
                ]
            )->asArray()->findAll();

        }

        if($data['block_id']) {
            $block = $this->block_model->find($data['block_id']);
            $data['block'] = $block->name;

            $data['fund_agency'] = $block->fund_agency_id ? (new CommonModel())->getFundAgency($block->fund_agency_id)['name']:'-';
        }
        $data['month_name'] = getMonthById($data['month_id'])['name'];
        $data['fin_year'] = getYear($data['year_id']);

        $this->filterPanel($data);

        if($action=='download'){
            $this->download($data,$components);
            exit;
        }

        $data['download_url'] = Url::mprDownload.'?year='.$data['year_id'].'&month='.$data['month_id'].'&agency_type_id='.$data['agency_type_id'];


        return $this->template->view('Admin\Reports\Views\mpr_abstract', $data);
    }

    public function abstractMpr() {

    }

    protected function filterPanel(&$data) {
        $data['years'] = getAllYears();
        $data['months'] = getAllMonths();
        $data['filter_panel'] = '';
        if($this->user->agency_type_id==$this->settings->district_user) {
            $filter = [
                'district_id' => $this->user->district_id,
            ];
            if($this->user->fund_agency_id){
                $filter['fund_agency_id'] = $this->user->fund_agency_id;
            }
            $data['blocks'] = $this->block_model->where($filter)->asArray()->findAll();
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

            $data['fund_agency_id'] = 1;
            if($this->request->getGet('fund_agency_id')){
                $data['fund_agency_id'] = $this->request->getGet('fund_agency_id');
            }

            $data['fund_agencies'] = (new BlockModel())->getFundAgencies(['asObject'=>true]);

            $data['districts'] = (new DistrictModel())->getDistrictsByFundAgency($data['fund_agency_id']);

            $data['blocks'] = $this->block_model->where([
                'district_id' => $this->request->getGet('district_id'),
                'fund_agency_id' => $this->request->getGet('fund_agency_id'),
            ])->asArray()->findAll();

            /*foreach ($this->settings->user_can_access as $user_group => $user_can_access_grp) {
                if(8==$user_group){
                    $data['agency_types'] = (new UserGroupModel())->whereIn('id',
                        $user_can_access_grp)->orderBy('name')->asArray()->findAll();
                }
            }*/

            $data['get_block_url'] = Url::getBlocks;
            $data['get_district_url'] = Url::getDistricts;

            $data['filter_panel'] = view('Admin\Reports\Views\state_filter_panel',$data);
        }
    }

    public function uploadStatus(){

        $data['year_id'] = getCurrentYearId();
        if($this->request->getGet('year')){
            $data['year_id'] = $this->request->getGet('year');
        }

        $data['month_id'] = getMonthIdByMonth(date('m'));
        if($this->request->getGet('month')){
            $data['month_id'] = $this->request->getGet('month');
        }

        return $this->template->view('Admin\Reports\Views\mpr_upload_status', $data);
    }

}

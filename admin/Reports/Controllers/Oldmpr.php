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

class Oldmpr extends AdminController
{

    public function index($action='') {
        $data = [];
		$data['years'] = $years = [
			1=>"2017-18",
			2=>"2018-19",
			3=>"2019-20",
			4=>"2020-21",
			5=>"2021-22"
		];
 
		$data['year_id'] = 1;
        if($this->request->getGet('year')){
            $data['year_id'] = $this->request->getGet('year');
        }
		
		if($this->request->getGet('download')){
            $download = true;
        }else{
			$download = false;
		}
		
		$data['fin_year'] = $years[$data['year_id']];
		$filter = [
            'year' => $data['year_id'],
        ];
		
		
		$client = \Config\Services::curlrequest();
		$response = $client->request('GET', 'https://soe1.milletsodisha.com/api/expense/mpr', [
			'auth' => ['X-API-KEY', '4o8c0ow0wooss4kswgwwcs4444swk0oc44gwc8gs'],
			'query' => $filter
		]);
		
		
		if($response->getStatusCode()==200){
			$data['cdata']=json_decode($response->getBody(),true);
		}else{
			$data['cdata']=[];
		}
		
		if($download){
            $this->download($data['cdata']);
            exit;
        }

        $data['download_url'] = admin_url('reports/oldmpr').'?year='.$data['year_id'].'&download=true';

		//printr($data['cdata']);
		//exit;
	
        return $this->template->view('Admin\Reports\Views\oldmpr', $data);
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

            $data['fund_agency_id'] = 1;
            if($this->request->getGet('fund_agency_id')){
                $data['fund_agency_id'] = $this->request->getGet('fund_agency_id');
            }

            $data['fund_agencies'] = (new BlockModel())->getFundAgencies(['asObject'=>true]);

            $data['get_block_url'] = Url::getBlocks;

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

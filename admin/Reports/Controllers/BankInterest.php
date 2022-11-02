<?php 
namespace Admin\Reports\Controllers;
use Admin\Reports\Models\ReportsModel;
use Admin\Users\Models\UserGroupModel;
use App\Controllers\AdminController;

class BankInterest extends AdminController
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

        $data['years'] = getAllYears();
        $data['months'] = getAllMonths();

        $data['agency_types'] = [];
        foreach ($this->settings->user_can_access as $user_group => $user_can_access_grp) {
            if($this->user->agency_type_id==$user_group){
                $data['agency_types'] = (new UserGroupModel())->whereIn('id',
                    $user_can_access_grp)->orderBy('name')->asArray()->findAll();
            }
        }

        $reportModel = new ReportsModel();
        $filter = [
            'year_id' => $data['year_id'],
            'month_id' => $data['month_id'],
            'district_id' => $this->user->district_id,
            'agency_type_id' => [5,6],
        ];
        if($data['agency_type_id']){
            $filter['agency_type_id'] = $data['agency_type_id'];
        }

        $total_int = 0;
        $fa_report = [];
        if($data['agency_type_id'] == ''
            || $data['agency_type_id'] == $this->settings->block_user
            || $data['agency_type_id'] == $this->settings->cbo_user) {
            $fa_report = $reportModel->getInterestReport($filter);
            foreach ($fa_report as &$item) {
                $item['int_total'] = ($item['int_upto'] + $item['int_mon']);
                $item['balance'] = ($item['int_total'] - $item['int_ref_block']);
                $total_int += $item['int_total'];
            }
        }

        $atma_ref = 0;
        $atma_report = [];
        if($data['agency_type_id'] == '' || $data['agency_type_id'] == $this->settings->district_user) {
            $filter['agency_type_id'] = 7;
            $atma_report = $reportModel->getInterestReport($filter);

            foreach ($atma_report as &$item) {
                $item['int_total'] = $item['int_upto'] + $item['int_mon'];
                $item['balance'] = '';
                $atma_ref += $item['int_ref_block'];
                $item['int_ref_block'] = '';
                $total_int += $item['int_total'];
            }

        }
        $data['sub_total'] = $total_int;
        $data['atma_ref'] = $atma_ref;
        $data['balance'] = $data['sub_total'] - $data['atma_ref'];

        $report = array_merge($fa_report,$atma_report);

        $data['report'] = $report;

        return $this->template->view('Admin\Reports\Views\bank_interest', $data);
    }
}

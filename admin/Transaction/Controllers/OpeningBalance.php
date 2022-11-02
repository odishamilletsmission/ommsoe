<?php

namespace Admin\Transaction\Controllers;

use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use Admin\Transaction\Models\ClosingbalanceModel;
use App\Controllers\AdminController;
use Config\Settings;
use Config\Url;

class OpeningBalance extends AdminController {

	public function index() {

        $obModel = new ClosingbalanceModel();

        $fund_agency_id = 0;
        if($this->user->agency_type_id==$this->settings->block_user){
            $fund_agency_id = (new BlockModel())->find($this->user->block_id)->fund_agency_id;
        }
        if($this->user->agency_type_id==$this->settings->district_user){
//            $fund_agency_id = (new DistrictModel())->find($this->user->district_id)->fund_agency_id;
            $fund_agency_id = $this->user->fund_agency_id;
        }

	    if($this->request->getMethod(1)=='POST'){

	        $ob = [
	            'month' => 0,
                'year' => 0,
                'block_id' => $this->user->block_id,
                'district_id' => $this->user->district_id,
                'user_id' => $this->user->user_id,
                'status' => 1,
                'advance' => $this->request->getPost('advance'),
                'bank' => $this->request->getPost('bank'),
                'cash' => $this->request->getPost('cash'),
                'agency_type_id' => $this->user->agency_type_id,
                'fund_agency_id' => $fund_agency_id,
            ];

            $obModel->insert($ob);

            //insert for cbo if block user
            if($this->user->agency_type_id==$this->settings->block_user) {
                $ob = [
                    'month' => 0,
                    'year' => 0,
                    'block_id' => $this->user->block_id,
                    'district_id' => $this->user->district_id,
                    'user_id' => $this->user->user_id,
                    'status' => 1,
                    'advance' => $this->request->getPost('advance'),
                    'bank' => $this->request->getPost('bank'),
                    'cash' => $this->request->getPost('cash'),
                    'agency_type_id' => $this->settings->cbo_user,
                    'fund_agency_id' => $fund_agency_id,
                ];
                $obModel->insert($ob);
            }

	        return redirect()->to(Url::dashboard);
        }

        $month_num = date('m');

        $date = date('d/m',mktime(0,0,0,$month_num,1)).'/'.getCurrentYear();

//	    $this->template->setMetaTitle('Opening balance');
//	    $this->template->setPageHeading('Opening balance as on '.$date);

	    $data['message'] = 'Please enter opening balance to continue. This is one time setup.';

	    helper('form');

        return $this->template->view('Admin\Transaction\Views\opening_balance',$data);
	}

}

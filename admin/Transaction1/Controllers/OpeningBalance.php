<?php

namespace Admin\Transaction\Controllers;

use Admin\Transaction\Models\ClosingbalanceModel;
use App\Controllers\AdminController;
use Config\Settings;
use Config\Url;

class OpeningBalance extends AdminController {

	public function index() {

        $obModel = new ClosingbalanceModel();

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

<?php
namespace Admin\UC\Controllers;

use Admin\Localisation\Models\BlockModel;
use Admin\UC\Models\UCAllotmentModel;
use Admin\UC\Models\UCSubmitModel;
use App\Controllers\AdminController;
use Config\Url;

class Allotment extends AdminController{
	
	public function index(){
        $this->template->add_package(['jquery_loading','datepicker'],true);

	    $data['years'] = getAllYears(false);
        $data['year_id'] = getCurrentYearId();

        if($this->request->getGet('year')){
            $data['year_id'] = $this->request->getGet('year');
        }

        $data['fund_agency_id'] = 1;
        if($this->request->getGet('fund_agency_id')){
            $data['fund_agency_id'] = $this->request->getGet('fund_agency_id');
        }

        $data['fund_agencies'] = (new BlockModel())->getFundAgencies(['asObject'=>true]);

        $data['year'] = getYear($data['year_id']);

        $data['add_url'] = site_url(Url::allotmentAdd).'?year='.$data['year_id'].'&fund_agency_id='.$data['fund_agency_id'];

        $data['allotments'] = [];

        $allotmentModel = new UCAllotmentModel();

        $filter = [
            'year' => $data['year_id'],
            'fund_agency_id' => $data['fund_agency_id']
        ];

        $recipients = $allotmentModel->getUCReport($filter);

        $data['recipients'] = [];
        $total_allotment = $uc_amount = $balance = $total_balance = 0;
        foreach ($recipients as $recipient) {
            $data['recipients'][] = [
                'recipient' => $recipient->recipient,
                'date_submit' => ymdToDmy($recipient->date_submit),
                'letter_no' => $recipient->letter_no,
                'total_allotment' => in_lakh($recipient->total_allotment),
                'uc_amount' => in_lakh($recipient->uc_amount),
                'balance' => in_lakh($recipient->total_allotment-$recipient->uc_amount),
                'total_balance' => in_lakh($recipient->total_allotment_upto-$recipient->total_submitted_upto),
                'action' => '<a href="'.site_url(Url::allotmentInfo).'?year='. $data['year_id'].'&recipient_id='.$recipient->recipient_id.'" class="btn btn-info">Details</a>'
            ];
            $total_allotment += $recipient->total_allotment;
            $uc_amount += $recipient->uc_amount;
            $balance += ($recipient->total_allotment-$recipient->uc_amount);
            $total_balance += ($recipient->total_allotment_upto-$recipient->total_submitted_upto);
        }
        $data['recipients'][] = [
            'recipient' => '<strong>Total</strong>',
            'date_submit' => '',
            'letter_no' => '',
            'total_allotment' => '<strong>'.in_lakh($total_allotment).'</strong>',
            'uc_amount' => '<strong>'.in_lakh($uc_amount).'</strong>',
            'balance' => '<strong>'.in_lakh($balance).'</strong>',
            'total_balance' => '<strong>'.in_lakh($total_balance).'</strong>',
            'action' => ''
        ];

        return $this->template->view('Admin\UC\Views\allotment', $data);
	}

    public function add() {

        if(!$this->request->isAJAX()){
            return $this->response->setStatusCode(404);
        }
        $allotmentModel = new UCAllotmentModel();

        $year = getCurrentYearId();
        if($this->request->getGet('year')){
            $year = $this->request->getGet('year');
        }

        $json_data = [
            'status' => false,
        ];

        if($this->request->getMethod(1)=='POST'){
            $allotmentModel
                ->insert([
                    'year'=> $this->request->getPost('year'),
                    'recipient_id' => $this->request->getPost('recipient_id'),
                    'allotment_date' => dmyToYmd($this->request->getPost('allotment_date')),
                    'amount' => $this->request->getPost('amount'),
                ]);

            $json_data = [
                'status' => true,
            ];
            $this->session->setFlashData('message','Allotment added.');
        } else {

            $json_data = [
                'status' => true,
                'title' => 'Add allotment',
                'html' => $this->getForm()
            ];
        }

        return $this->response->setJSON($json_data);
	}

    public function info() {
        $this->template->add_package(['jquery_loading','datepicker'],true);

        $year = $this->request->getGet('year');
        $recipient_id = $this->request->getGet('recipient_id');

        $filter = [
            'year' => $year,
            'recipient_id' => $recipient_id
        ];

        $allotmentModel = new UCAllotmentModel();
        $ucModel = new UCSubmitModel();

        $allotments = $allotmentModel->getAllotments($filter);

        $ids = [];
        $data['allotments'] = [];
        $data['total_allotment'] = 0;
        foreach ($allotments as $allotment) {
            $ids[] = $allotment->allotment_id;

            $data['allotments'][] = [
                'recipient' => $allotment->recipient,
                'year' => $allotment->year,
                'amount' => $allotment->amount,
                'allotment_date' => ymdToDmy($allotment->allotment_date),
                'action' => '<a href="'.site_url(Url::allotmentEdit).'?allotment_id='.$allotment->allotment_id.'" class="btn btn-info btn-edit">Edit</a>',
            ];

            $data['total_allotment'] += $allotment->amount;
        }

        $submissions = [];
        if($ids) {
            $submissions = $ucModel->whereIn('allotment_id', $ids)->findAll();
        }
        $data['submissions'] = [];

        $data['total_uc'] = 0;
        foreach ($submissions as $submission) {
            $data['submissions'][] = [
                'date_submit' => ymdToDmy($submission->date_submit),
                'letter_no' => $submission->letter_no,
                'page_no' => $submission->page_no,
                'amount' => $submission->amount,
                'document' => $submission->document ? site_url($submission->document):'',
            ];
            $data['total_uc'] += $submission->amount;
        }

        $data['add_url'] = site_url(Url::allotmentAdd).'?year='.$year;

        return $this->template->view('Admin\UC\Views\allotment_info', $data);
	}

    public function edit() {
        if(!$this->request->isAJAX()){
            return $this->response->setStatusCode(404);
        }
        $allotmentModel = new UCAllotmentModel();

        if($this->request->getGet('allotment_id')){
            $allotment_id = $this->request->getGet('allotment_id');
        }

        $json_data = [
            'status' => false,
        ];

        if($this->request->getMethod(1)=='POST'){
            $allotmentModel
                ->update($allotment_id,[
                    'year'=> $this->request->getPost('year'),
                    'recipient_id' => $this->request->getPost('recipient_id'),
                    'allotment_date' => dmyToYmd($this->request->getPost('allotment_date')),
                    'amount' => $this->request->getPost('amount'),
                ]);

            $json_data = [
                'status' => true,
            ];
            $this->session->setFlashData('message','Allotment updated.');
        } else {

            $json_data = [
                'status' => true,
                'title' => 'Edit allotment',
                'html' => $this->getForm()
            ];
        }
        return $this->response->setJSON($json_data);
	}

    protected function getForm(){

        helper('form');
        $allotmentModel = new UCAllotmentModel();

        $year = getCurrentYearId();
        if($this->request->getGet('year')){
            $year = $this->request->getGet('year');
        }
        $allotment_id = '';
        if($this->request->getGet('allotment_id')){
            $allotment_id = $this->request->getGet('allotment_id');
        }

        if ($allotment_id) {
            $allotment = $allotmentModel->find($allotment_id);

            $data['year_id'] = $allotment->year;
            $data['recipient_id'] = $allotment->recipient_id;
            $data['amount'] = $allotment->amount;
            $data['allotment_date'] = ymdToDmy($allotment->allotment_date);

        } else {

            $data['year_id'] = $year;
            $data['recipient_id'] = '';
            $data['amount'] = '';
            $data['allotment_date'] = '';
        }

        $data['years'] = getAllYears();
        $data['recipients'] = $allotmentModel->getAllRecipients();

        return view('\Admin\UC\Views\allotment_form', $data);
    }

}

/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */
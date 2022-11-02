<?php
namespace Admin\UC\Controllers;

use Admin\UC\Models\UCAllotmentModel;
use Admin\UC\Models\UCSubmitModel;
use App\Controllers\AdminController;
use Config\Url;

class Submit extends AdminController{
	
	public function index(){
        $data = [];

        $ucModel = new UCSubmitModel();

        if($this->user->district_id) {
            $filter = ['district_id' => $this->user->district_id];
        } else {
            if($this->user->agency_type_id==$this->settings->ps_user){
                $filter = ['recipient_id' => 23];
            }
            if($this->user->agency_type_id==$this->settings->rs_user){
                $filter = ['recipient_id' => 15];
            }
        }
        $allotments = $ucModel->getUCReport($filter);

        $data['allotments'] = [];
        $data['total_allotment'] = $data['total_uc_submitted'] = $data['total_uc_balance'] = 0;
        foreach ($allotments as $allotment) {
            $data['allotments'][] = [
                'year' => $allotment->year,
                'uc_date' => ymdToDmy($allotment->date_submit),
                'letter_no' => $allotment->letter_no,
                'allotment' => $allotment->allotment,
                'uc_submitted' => $allotment->uc_submit,
                'uc_balance' => $allotment->balance,
                'action' => '<a href="'.site_url(Url::ucSubmitInfo).'?year_id='.$allotment->year_id.'" class="btn btn-info"><i class="fa fa-send"></i> View</a>'
            ];
            $data['total_allotment'] += $allotment->allotment;
            $data['total_uc_submitted'] += $allotment->uc_submit;
            $data['total_uc_balance'] += $allotment->balance;
        }

        return $this->template->view('Admin\UC\Views\submit', $data);
	}

    public function info() {
	    $data = [];
        $this->template->add_package(['jquery_loading','datepicker','uploader'],true);

	    $year = getCurrentYearId();
	    if($this->request->getGet('year_id')){
	        $year = $this->request->getGet('year_id');
        }

	    $allModel = new UCAllotmentModel();
	    $subModel = new UCSubmitModel();

	    $recipient_id = $subModel->getRecipientId($this->user->district_id);

	    $allotments = $allModel->getAllotments(['year'=>$year,'recipient_id'=>$recipient_id]);

	    $data['allotments'] = [];
	    $data['ucs'] = [];
        $data['total_allotment'] = $data['total_uc_submitted'] = 0;
        foreach ($allotments as $allotment) {
            $data['allotments'][] = [
                'year' => $allotment->year,
                'allotment_date' => ymdToDmy($allotment->allotment_date),
                'amount' => $allotment->amount,
                'action' => '<a href="'.site_url(Url::ucSubmitAdd).'?allotment_id='.$allotment->allotment_id.'" class="btn btn-success add-new">Submit UC</a>'
            ];

            $data['total_allotment'] += $allotment->amount;
	    }

	    $ucs = $subModel->getSubmissions(['year'=>$year,'recipient_id'=>$recipient_id]);

        foreach ($ucs as $uc) {
            $data['ucs'][] = [
                'uc_date' => ymdToDmy($uc->date_submit),
                'letter_no' => $uc->letter_no,
                'page_no' => $uc->page_no,
                'uc_amount' => $uc->amount,
                'uc_document' => $uc->document ? site_url($uc->document):'',
                'action' => $this->user->district_id ? site_url(Url::ucSubmitEdit).'?id='.$uc->uc_id:''
            ];

            $data['total_uc_submitted'] += $uc->amount;
        }

        $data['upload_url'] = site_url(Url::ucSubmitUpload);

        return $this->template->view('Admin\UC\Views\submit_info', $data);
	}

    public function add() {

        $json_data = [
            'status' => false,
        ];
        if($this->request->getMethod(1)=='POST'){

            $uc = [
                'allotment_id' => $this->request->getGet('allotment_id'),
                'date_submit' => dmyToYmd($this->request->getPost('date_submit')),
                'letter_no' => $this->request->getPost('letter_no'),
                'page_no' => $this->request->getPost('page_no'),
                'amount' => $this->request->getPost('amount'),
                'document' => $this->request->getPost('document')
            ];

            (new UCSubmitModel())->insert($uc);

            $json_data = [
                'status' => true,
            ];
            $this->session->setFlashData('message','UC added.');
        } else {
            $json_data = [
                'status' => true,
                'title' => 'Add UC Submission',
                'html' => $this->getForm()
            ];
        }
        return $this->response->setJSON($json_data);
	}

    public function edit() {

        $json_data = [
            'status' => false,
        ];
        $id = $this->request->getGet('id');
	    if($this->request->getMethod(1)=='POST'){

            $uc = [
                'date_submit' => dmyToYmd($this->request->getPost('date_submit')),
                'letter_no' => $this->request->getPost('letter_no'),
                'page_no' => $this->request->getPost('page_no'),
                'amount' => $this->request->getPost('amount'),
                'document' => $this->request->getPost('document')
            ];

            (new UCSubmitModel())->update($id,$uc);

            $json_data = [
                'status' => true,
            ];
            $this->session->setFlashData('message','UC updated.');
        } else {
            $json_data = [
                'status' => true,
                'title' => 'Edit UC Submission',
                'html' => $this->getForm()
            ];
        }
        return $this->response->setJSON($json_data);
	}

    protected function getForm() {
	    $data = [];

        $data['date_submit'] = '';
        $data['amount'] = '';
        $data['letter_no'] = '';
        $data['page_no'] = '';
        $data['document'] = '';
        $data['document_name'] = '';
        $data['document_url'] = '';

	    if($this->request->getGet('id')) {
            $submission = (new UCSubmitModel())->find($this->request->getGet('id'));

            $data['date_submit'] = ymdToDmy($submission->date_submit);
            $data['amount'] = $submission->amount;
            $data['letter_no'] = $submission->letter_no;
            $data['page_no'] = $submission->page_no;
            $data['document'] = $submission->document;
            if ($submission->document) {
                $file = DIR_UPLOAD . $submission->document;
                $fileinfo = pathinfo($file);
                $filename = $fileinfo['basename'];
                $data['document_name'] = $filename;
                $data['document_url'] = '<a href="' . site_url($submission->document) . '">' . $filename . '</a>';
            }
        }

        return view('\Admin\UC\Views\submit_form', $data);
    }

    public function upload() {
        $input = $this->validate([
            'document' => [
                'uploaded[document]',
                'mime_in[document,application/pdf]',
                'max_size[document,2048]',
                'ext_in[document,pdf,PDF]',
            ]
        ]);

        if (!$input) {
            $data = [
                'status' => false,
                'message' => 'Invalid file',
                'errors' => $this->validator->getErrors()
            ];
        } else {
            $file = $this->request->getFile('document');
            $file->move(DIR_UPLOAD . 'uc');
            $data = [
                'status'=>true,
                'message'=> '<a target="_blank" href="'.base_url('uploads/uc/'.$file->getName()).'">'.$file->getName().'</a>',
                'filename' => $file->getName(),
                'filepath' => 'uploads/uc/'.$file->getName()
            ];
        }
        return $this->response->setJSON($data);
    }

}

/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */
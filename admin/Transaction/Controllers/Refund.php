<?php 
namespace Admin\Transaction\Controllers;
use Admin\Common\Models\AllowuploadModel;
use Admin\Common\Models\CommonModel;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use Admin\Transaction\Models\TransactionComponentModel;
use Admin\Transaction\Models\TransactionModel;
use Admin\Users\Models\UserGroupModel;
use App\Controllers\AdminController;
use App\Traits\TreeTrait;
use Config\ExcelStyles;
use Config\Url;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Refund extends AdminController {

    use TreeTrait;

    public function index() {

        $this->template->add_package(['datatable','uploader','jquery_loading'],true);
        $data = [];
        $data['months'] = getMonths();
        $data['years'] = getAllYears();

        $upload_model = new AllowuploadModel();
        $block_model = new BlockModel();

        $date = date('Y-m-d');

        $upload = $upload_model->groupStart()->orWhere([
            'from_date >='=>$date,
            'to_date <='=>$date])->groupEnd()->where([
            'block_id'=>$this->user->block_id
        ])->find();

        $data['upload_enabled'] = $upload['enabled'];
        $data['download_button'] = $this->user->agency_type_id==$this->settings->block_user;
        $data['month_id'] = $upload['month'];
        $data['year_id'] = $upload['year'];

        $data['datatable_url'] = Url::refundDatatable;
        $data['add_url'] = Url::refundAdd;
        $data['message'] = $this->session->getFlashdata('message');

        $data['blocks'] = [];
        if($this->user->agency_type_id!=$this->settings->block_user) {
            $data['blocks'] = $block_model->where(['district_id' => $this->user->district_id])->asArray()->findAll();
        }
        $data['districts'] = [];
        if($this->user->agency_type_id!=$this->settings->district_user
            && $this->user->agency_type_id!=$this->settings->block_user) {
            $data['districts'] = (new DistrictModel)->asArray()->findAll();
        }
        $data['agency_types'] = [];
        foreach ($this->settings->user_can_access as $user_group => $user_can_access_grp) {
            if($this->user->agency_type_id==$user_group){
                $data['agency_types'] = (new UserGroupModel)->whereIn('id',
                    $user_can_access_grp)->orderBy('name')->asArray()->findAll();
            }
        }
        $data['agency_type_id'] = $this->user->agency_type_id;

        $data['fund_agencies'] = [];
        if($this->user->agency_type_id!=$this->settings->block_user){
            if($this->user->district_id)
                $data['fund_agencies'] = $block_model->getFundAgencies(['district_id'=>$this->user->district_id]);
            else
                $data['fund_agencies'] = $block_model->getFundAgencies();
        }

        return $this->template->view('Admin\Transaction\Views\refund', $data);
    }

    public function search() {
        if(!$this->request->isAJAX()){
            return $this->response->setStatusCode(404);
        }

        $txnModel = new TransactionModel();
        $requestData = $_REQUEST;
        $totalData = $txnModel->getTotal(['user_id'=>$this->user->user_id]);
        $totalFiltered = $totalData;

        $filter_search = $requestData['search']['value'];

        $order_columns = array(
            'block','month','year','date_added'
        );
        $filter_data = array(
            'user_id' => $this->user->user_id,
            'transaction_type' => 'refund',
            'filter_search' => $filter_search,
            'order' => $requestData['order'][0]['dir'],
            'sort' => $order_columns[$requestData['order'][0]['column']],
            'start' => $requestData['start'],
            'limit' => $requestData['length']
        );
//        $totalFiltered = 0;
        $totalFiltered = $txnModel->getTotal($filter_data);

        $filteredData = $txnModel->getAll($filter_data);

        $datatable=array();

        foreach($filteredData as $result) {

            $action  = '<div class="btn-group">';
            $action .= '<a class="btn btn-sm btn-primary" href="' . Url::refundEdit.'/'.$result->id . '"><i class="fa fa-pencil"></i></a>';
            $action .= '</div>';
            if($result->status==0){
                $status = '<label class="badge badge-warning">'.$this->statuses[$result->status].'</label>';
            }
            if($result->status==1){
                $status = '<label class="badge badge-success">'.$this->statuses[$result->status].'</label>';
            }
            if($result->status==2){
                $status = '<label class="badge badge-danger">'.$this->statuses[$result->status].'</label>';
            }

            $txn_type = '<label class="badge badge-danger">Refund</label>';

            $datatable[]=array(
                $result->month,
                $result->year,
                ymdToDmy($result->date_added),
                $txn_type,
                $result->agency_type,
                $result->phy,
                $result->fin,
                $status,
                $action
            );

        }
        //printr($datatable);
        $json_data = array(
            "draw"            => isset($requestData['draw']) ? intval( $requestData['draw'] ):1,
            "recordsTotal"    => intval( $totalData ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $datatable
        );
        ob_end_clean();
        return $this->response->setJSON($json_data);

    }

    public function add() {

        $txnModel = new TransactionModel();
        $txnCompModel = new TransactionComponentModel();

        $block_id = $this->user->block_id;
        if($this->request->getGet('block_id')) {
            $block_id = $this->request->getGet('block_id');
        }
        $district_id = $this->user->district_id;
        if($this->request->getGet('district_id')) {
            $district_id = $this->request->getGet('district_id');
        }

        if($this->user->agency_type_id == $this->settings->district_user){
            $district_id = $this->user->district_id;
        }
        $agency_type_id = $this->user->agency_type_id;
        if($this->request->getGet('agency_type_id')) {
            $agency_type_id = $this->request->getGet('agency_type_id');
        }
        $fund_agency_id = 0;
        if($this->request->getGet('fund_agency_id')) {
            $fund_agency_id = $this->request->getGet('fund_agency_id');
        }
        $txn_type = 'refund';
        $month = $this->request->getGet('month');
        $year = $this->request->getGet('year');

        if($block_id){
            $fund_agency_id = (new BlockModel())->find($block_id)->fund_agency_id;
        }
        if(!$txn_type || !$fund_agency_id){
            $this->session->setFlashdata('message','Invalid request!!');
            return redirect()->to(Url::refund);
        }

        $data = [];
        if($this->request->getMethod(1)=='POST'){

            $txn_data = [
                'block_id'=>$block_id,
                'district_id'=>$district_id,
                'agency_type_id'=>$agency_type_id,
                'month' => $month,
                'year' => $year,
                'filename' => '',
                'status' => (int)in_array($this->user->agency_type_id,$this->settings->auto_approve_users),
                'date_added' => date('Y-m-d'),
                'user_id' => $this->user->user_id,
                'transaction_type' => $txn_type,
                'fund_agency_id' => $fund_agency_id
            ];
            $txn_id = $txnModel->insert($txn_data);

            $components = [];

            foreach ($this->request->getPost() as $component_id => $value) {
                $components[] = [
                    'transaction_id' => $txn_id,
                    'component_id' => $component_id,
                    'physical' => $value['phy'],
                    'financial' => $value['fin']
                ];
            }
            $txnCompModel->insertBatch($components);

            $this->session->setFlashdata('message','New transaction added');
            return redirect()->to(Url::refund);
        }

        //validate if transaction exists
        $txn = $txnModel->where([
            'block_id'=>$block_id,
            'district_id'=>$district_id,
            'agency_type_id'=>$agency_type_id,
            'month' => $month,
            'year' => $year,
            'user_id' => $this->user->user_id,
            'transaction_type' => $txn_type,
            'fund_agency_id' => $fund_agency_id
        ])->first();

        if($txn){
            $this->session->setFlashdata('message','Cannot add transaction. Transaction already exists!!');
            return redirect()->to(Url::refund);
        }

        $data['show_form'] = true;

        $data['block'] = $block_id ? (new BlockModel)->find($block_id)->name:'-';
        $data['district'] = $district_id ? (new DistrictModel)->find($district_id)->name:'-';
        $data['agency_type'] = $agency_type_id ? (new UserGroupModel)->find($agency_type_id)->name:'-';
        $data['fund_agency'] = $fund_agency_id ? (new CommonModel())->getFundAgency($fund_agency_id)['name']:'-';
        $data['month'] = getMonthById($month)['name'];
        $data['year'] = getYear($year);
        $data['date_added'] = date('Y/m/d');
        $data['phy'] = '-';
        $data['fin'] = '-';

        $data['status'] = '-';

        $data['remarks'] = '-';

        $data['txn_type_text'] = 'Refund';

        $filter = [
            'user_id' => $this->user->user_id,
            'block_id' => $block_id,
            'month' => $month,
            'year' => $year,
        ];

        if($district_id){
            $filter['district_id'] = $district_id;
        }
        if(in_array($this->user->agency_type_id,[$this->settings->ps_user,$this->settings->rs_user])) {
            $filter['user_group'] = $this->user->agency_type_id;
        }

        $block_components = $txnModel->getRefund($filter);

        foreach ($block_components as &$component) {
            $component['fr_mon_phy'] = '';
            $component['fr_mon_fin'] = '';
        }

        $components = $this->buildTree($block_components,'parent','component_id');

        $data['components'] = $this->getTable($components,$txn_type,'edit');

        $data['fund_agencies'] = [];
        if($this->user->agency_type_id!=$this->settings->block_user){
            if($district_id)
                $data['fund_agencies'] = (new BlockModel())->getFundAgencies(['district_id'=>$district_id]);
            else
                $data['fund_agencies'] = (new BlockModel())->getFundAgencies();
        }

        return $this->template->view('Admin\Transaction\Views\refund_edit', $data);
    }

    public function edit() {
        $txnModel = new TransactionModel();
        $txnCompModel = new TransactionComponentModel();
        $data = [];

        if($this->request->getMethod(1)=='POST'){
            $id = $this->uri->getSegment(4);

            $txn = $txnModel->find($this->uri->getSegment(4));
            $txnModel->delete($id);

            $txn_data = [
                'block_id'=>$txn->block_id,
                'district_id'=>$txn->district_id,
                'agency_type_id'=>$txn->agency_type_id,
                'month' => $txn->month,
                'year' => $txn->year,
                'filename' => $txn->filename,
                'status' => (int)in_array($this->user->agency_type_id,$this->settings->auto_approve_users),
                'date_added' => date('Y-m-d'),
                'user_id' => $txn->user_id,
                'transaction_type' => $txn->transaction_type,
                'fund_agency_id' => $txn->fund_agency_id
            ];
            $txn_id = $txnModel->insert($txn_data);

            //delete the existing transaction components
            $txnCompModel->where(['transaction_id'=>$id])->delete();
            $components = [];

            foreach ($this->request->getPost() as $component_id => $value) {
                $components[] = [
                    'transaction_id' => $txn_id,
                    'component_id' => $component_id,
                    'physical' => $value['phy'],
                    'financial' => $value['fin']
                ];
            }
            $txnCompModel->insertBatch($components);

            $this->session->setFlashdata('message','Your changes have been saved');
            return redirect()->to(Url::refund);
        }

        $txn = new \stdClass();
        if ($this->uri->getSegment(5) && ($this->request->getMethod(true) != 'POST')) {
            $txn = $txnModel->find($this->uri->getSegment(5));

            if(!$txn){
                $this->session->setFlashdata('message','Transaction not found!');
                return redirect()->to(Url::refund);
            }

            //validate user transaction
            if(!$txnModel->canEdit($txn)){
                $this->session->setFlashdata('message','You cannot edit this transaction');
                return redirect()->to(Url::refund);
            }
        }

        if($txn->status==0){
            $action = 'edit';
            $data['show_form'] = true;
        } else {
            $action = 'view';
            $data['show_form'] = false;
        }

        //editing override for atma
        if($this->user->agency_type_id != $this->settings->block_user){
            $action = 'edit';
            $data['show_form'] = true;
        }

        $data['block'] = $txn->block;
        $data['district'] = $txn->district;
        $data['agency_type'] = (new UserGroupModel())->find($txn->agency_type_id)->name;
        $data['fund_agency'] = $txn->fund_agency_id ? (new CommonModel())->getFundAgency($txn->fund_agency_id)['name']:'-';
        $data['month'] = getMonthById($txn->month)['name'];
        $data['year'] = getYear($txn->year);
        $data['date_added'] = ymdToDmy($txn->date_added);
        $data['phy'] = $txn->physical;
        $data['fin'] = $txn->financial;

        $data['status'] = $this->statuses[$txn->status];

        $data['remarks'] = $txn->remarks;

        $data['txn_type_text'] = $txn->transaction_type=='expense'?'Expense':'Fund Receipt';

        $filter = [
            'user_id' => $txn->user_id,
            'block_id' => $txn->block_id,
            'district_id' => $txn->district_id,
            'month' => $txn->month,
            'year' => $txn->year,
            'agency_type_id' => $txn->agency_type_id,
        ];

        $block_components = $txnModel->getRefund($filter);

        $components = $this->buildTree($block_components,'parent','component_id');

        $data['components'] = $this->getTable($components,$txn->transaction_type,$action);

        return $this->template->view('Admin\Transaction\Views\refund_edit', $data);
    }

    protected function getTable($array,$txn_type,$action) {
        $this->tot_ob_phy = $this->tot_ob_fin = $this->tot_upto_phy = $this->tot_upto_fin = 0;
        $this->tot_mon_phy = $this->tot_mon_fin = $this->tot_cum_phy = $this->tot_cum_fin = 0;

        $html = $this->generateTable($array,$txn_type,$action);

        //grand total
        $html .= '<tr class="subtotal bg-yellow">
                    <td colspan="2">Grand Total</td>
                    <td>'.$this->tot_ob_phy.'</td>
                    <td>'.$this->tot_ob_fin.'</td>
                    <td id="gt_mon_phy">'.$this->tot_mon_phy.'</td>
                    <td id="gt_mon_fin">'.$this->tot_mon_fin.'</td>
                    </tr>
                ';

        return $html;

    }

    protected function generateTable($array,$txn_type,$action='view') {
        $html = '';
        $this->ob_phy = $this->ob_fin = $this->upto_phy = $this->upto_fin = 0;
        $this->mon_phy = $this->mon_fin = $this->cum_phy = $this->cum_fin = 0;

        foreach ($array as $item) {
            if($item['row_type']=='heading') {
                $html .= '<tr class="heading">
                    <th>' . $item['number'] . '</th>
                    <th>' . $item['description'] . '</th>
                    <th colspan="4"></th>
                    </tr>
                ';
            } else {
                $html .= '<tr data-parent="'.$item['parent'].'">
                    <td>' . $item['number'] . ' </td>
                    <td>' . $item['description'] . ' </td>
                    <td>' . $item['ob_phy'] . ' </td>
                    <td>' . $item['ob_fin'] . ' </td>';
                if($action=='edit'){
                    $html .= '<td class="mon_phy"><input class="w-50p" name="'.$item['component_id'].'[phy]" type="text" value="' . $item['fr_mon_phy'] . '"> </td>
                                  <td class="mon_fin"><input type="text" name="'.$item['component_id'].'[fin]" value="' . $item['fr_mon_fin'] . '"> </td>';
                } else {
                    $html .= '<td class="mon_phy">' . $item['fr_mon_phy'] . ' </td>
                                  <td class="mon_fin">' . $item['fr_mon_fin'] . ' </td>';
                }
                $html .= '</tr>';

                $component = $item;
                //sub total
                $this->ob_phy += $component['ob_phy'];
                $this->ob_fin += $component['ob_fin'];

                $this->mon_phy += (int)$component['fr_mon_phy'];
                $this->mon_fin += (float)$component['fr_mon_fin'];

                //total
                $this->tot_ob_phy += $component['ob_phy'];
                $this->tot_ob_fin += $component['ob_fin'];
                $this->tot_mon_phy += (int)$component['fr_mon_phy'];
                $this->tot_mon_fin += (float)$component['fr_mon_fin'];
            }
            if (!empty($item['children'])){
                $html .= $this->generateTable($item['children'],$txn_type,$action);
                $html .= '<tr class="subtotal" data-parent="'.$item['component_id'].'">
                    <td colspan="2">Sub Total</td>
                    <td>'.$this->ob_phy.'</td>
                    <td>'.$this->ob_fin.'</td>
                    <td class="sub_mon_phy">'.$this->mon_phy.'</td>
                    <td class="sub_mon_fin">'.$this->mon_fin.'</td>
                    </tr>
                ';
            }
        }

        return $html;

    }
}

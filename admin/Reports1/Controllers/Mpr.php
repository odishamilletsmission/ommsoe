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
use App\Traits\TreeTrait;

class Mpr extends AdminController
{
    use TreeTrait;

    public function index() {
        $data = [];

        return $this->template->view('Admin\Reports\Views\index', $data);
    }

    public function block() {
        $data = [];
        $txnModel = new TransactionModel();

        $data['year_id'] = getCurrentYearId();
        if($this->request->getGet('year')){
            $data['year_id'] = $this->request->getGet('year');
        }

        $data['from_month'] = getMonthIdByMonth(date('m'));
        if($this->request->getGet('from_month')){
            $data['from_month'] = $this->request->getGet('from_month');
        }

        $data['to_month'] = getMonthIdByMonth(date('m'));
        if($this->request->getGet('to_month')){
            $data['to_month'] = $this->request->getGet('to_month');
        }

        $data['month_id'] = getMonthIdByMonth(date('m'));
        if($this->request->getGet('month')){
            $data['month_id'] = $this->request->getGet('month');
        }

        $data['agency_type_id'] = '';
        if($this->request->getGet('agency_type_id')){
            $data['agency_type_id'] = $this->request->getGet('agency_type_id');
        }

        $data['block_id'] = $this->user->block_id;
        if($this->request->getGet('block_id')){
            $data['block_id'] = $this->request->getGet('block_id');
        }

        $where = ['block_id'=>$data['block_id'],'user_group_id'=>$this->settings->block_user];
        $user = (new UserModel())->where($where)->first();

        $data['agency_types'] = [];
        foreach ($this->settings->user_can_access as $user_group => $user_can_access_grp) {
            if($this->user->agency_type_id==$user_group){
                $data['agency_types'] = (new UserGroupModel())->whereIn('id',
                    $user_can_access_grp)->orderBy('name')->asArray()->findAll();
            }
        }

        $data['districts'] = [];
        if($this->user->agency_type_id!=$this->settings->district_user
            && $this->user->agency_type_id!=$this->settings->block_user) {
            $data['districts'] = (new DistrictModel())->asArray()->findAll();
        }

        $block_model = new BlockModel();

        $data['blocks'] = [];
        if($this->user->agency_type_id!=$this->settings->block_user) {
            $data['blocks'] = $block_model->where(['district_id' => $user->district_id])->asArray()->findAll();
        }

        $data['fund_agencies'] = [];
        if($this->user->agency_type_id!=$this->settings->block_user){
            if($this->user->district_id)
                $data['fund_agencies'] = $block_model->getFundAgencies(['district_id'=>$this->user->district_id]);
            else
                $data['fund_agencies'] = $block_model->getFundAgencies();
        }

        $data['periodic'] = false;

        $data['components'] = [];
        $filter = [
            'user_id' => $user->id,
            'block_id' => $user->block_id,
            'district_id' => $user->district_id,
//            'from_month' => $data['from_month'],
//            'to_month' => $data['to_month'],
            'month_id' => $data['month_id'],
            'year_id' => $data['year_id'],
        ];
        $reportModel = new ReportsModel();

        $components = $reportModel->getMpr($filter);
        $components = $this->buildTree($components,'parent','component_id');

        $data['components'] = $this->getTable($components,'view');

        $block = $block_model->find($data['block_id']);
        $data['years'] = getAllYears();
        $data['months'] = getAllMonths();
        $data['district'] = (new DistrictModel())->find($user->district_id)->name;
        $data['block'] = $block->name;
        $data['month_name'] = getMonthById($data['month_id'])['name'];
        $data['fin_year'] = getYear($data['year_id']);
        $data['fund_agency'] = $block->fund_agency_id ? (new CommonModel())->getFundAgency($block->fund_agency_id)['name']:'-';;

        return $this->template->view('Admin\Reports\Views\mpr_block', $data);
    }

    protected function getTable($array,$action) {
        $this->tot_ob_phy = $this->tot_ob_fin = $this->tot_fr_upto_phy = $this->tot_fr_upto_fin = 0;
        $this->tot_fr_mon_phy = $this->tot_fr_mon_fin = $this->tot_fr_cum_phy = $this->tot_fr_cum_fin = 0;

        $this->tot_cb_phy = $this->tot_cb_fin = $this->tot_exp_upto_phy = $this->tot_exp_upto_fin = 0;
        $this->tot_exp_mon_phy = $this->tot_exp_mon_fin = $this->tot_exp_cum_phy = $this->tot_exp_cum_fin = 0;

        $html = $this->generateTable($array,$action);

        //grand total
        $html .= '<tr class="subtotal bg-yellow">
                    <td colspan="2">Grand Total</td>
                    <td>'.$this->tot_ob_phy.'</td>
                    <td>'.in_lakh($this->tot_ob_fin).'</td>
                    <td>'.$this->tot_fr_upto_phy.'</td>
                    <td>'.in_lakh($this->tot_fr_upto_fin).'</td>
                    <td id="gt_mon_phy">'.$this->tot_fr_mon_phy.'</td>
                    <td id="gt_mon_fin">'.in_lakh($this->tot_fr_mon_fin).'</td>
                    <td id="gt_cum_phy">'.$this->tot_fr_cum_phy.'</td>
                    <td id="gt_cum_fin">'.in_lakh($this->tot_fr_cum_fin).'</td>
                    <td>'.$this->tot_exp_upto_phy.'</td>
                    <td>'.in_lakh($this->tot_exp_upto_fin).'</td>
                    <td id="gt_mon_phy">'.$this->tot_exp_mon_phy.'</td>
                    <td id="gt_mon_fin">'.in_lakh($this->tot_exp_mon_fin).'</td>
                    <td id="gt_cum_phy">'.$this->tot_exp_cum_phy.'</td>
                    <td id="gt_cum_fin">'.in_lakh($this->tot_exp_cum_fin).'</td>
                    <td>'.$this->tot_cb_phy.'</td>
                    <td>'.in_lakh($this->tot_cb_fin).'</td>
                    </tr>
                ';

        return $html;

    }

    protected function generateTable($array,$action='view') {
        $html = '';

        $this->ob_phy = $this->ob_fin = $this->fr_upto_phy = $this->fr_upto_fin = 0;
        $this->fr_mon_phy = $this->fr_mon_fin = $this->fr_cum_phy = $this->fr_cum_fin = 0;

        $this->cb_phy = $this->cb_fin = $this->exp_upto_phy = $this->exp_upto_fin = 0;
        $this->exp_mon_phy = $this->exp_mon_fin = $this->exp_cum_phy = $this->exp_cum_fin = 0;

        foreach ($array as $item) {
            if($item['row_type']=='heading') {
                $html .= '<tr class="heading">
                    <th>' . $item['number'] . '</th>
                    <th>' . $item['description'] . '</th>
                    <th colspan="16"></th>
                    </tr>
                ';
            } else {
                $html .= '<tr data-parent="'.$item['parent'].'">
                    <td>' . $item['number'] . ' </td>
                    <td>' . $item['description'] . ' </td>
                    <td>' . $item['ob_phy'] . ' </td>
                    <td>' . $item['ob_fin'] . ' </td>
                    <td class="upto_phy">' . $item['fr_upto_phy'] . ' </td>
                    <td class="upto_fin">' . in_lakh($item['fr_upto_fin']) . ' </td>
                    <td class="mon_phy">' . $item['fr_mon_phy'] . ' </td>
                    <td class="mon_fin">' . in_lakh($item['fr_mon_fin']) . ' </td>
                    <td class="cum_phy">' . $item['fr_cum_phy'] . ' </td>
                    <td class="cum_fin">' . in_lakh($item['fr_cum_fin']) . ' </td>
                    <td class="upto_phy">' . $item['exp_upto_phy'] . ' </td>
                    <td class="upto_fin">' . in_lakh($item['exp_upto_fin']) . ' </td>
                    <td class="mon_phy">' . $item['exp_mon_phy'] . ' </td>
                    <td class="mon_fin">' . in_lakh($item['exp_mon_fin']) . ' </td>
                    <td class="cum_phy">' . $item['exp_cum_phy'] . ' </td>
                    <td class="cum_fin">' . in_lakh($item['exp_cum_fin']) . ' </td>
                    <td>' . $item['cb_phy'] . ' </td>
                    <td>' . in_lakh($item['cb_fin']) . ' </td>
                    ';
                $html .= '</tr>';

                $component = $item;
                //sub total
                $this->ob_phy += $component['ob_phy'];
                $this->ob_fin += $component['ob_fin'];
                $this->exp_upto_phy += (int)$component['exp_upto_phy'];
                $this->exp_upto_fin += (float)$component['exp_upto_fin'];
                $this->exp_mon_phy += (int)$component['exp_mon_phy'];
                $this->exp_mon_fin += (float)$component['exp_mon_fin'];
                $this->exp_cum_phy += (int)$component['exp_cum_phy'];
                $this->exp_cum_fin += (float)$component['exp_cum_fin'];
                $this->fr_upto_phy += (int)$component['fr_upto_phy'];
                $this->fr_upto_fin += (float)$component['fr_upto_fin'];
                $this->fr_mon_phy += (int)$component['fr_mon_phy'];
                $this->fr_mon_fin += (float)$component['fr_mon_fin'];
                $this->fr_cum_phy += (int)$component['fr_cum_phy'];
                $this->fr_cum_fin += (float)$component['fr_cum_fin'];
                $this->cb_phy += $component['cb_phy'];
                $this->cb_fin += $component['cb_fin'];

                //total
                $this->tot_ob_phy += $component['ob_phy'];
                $this->tot_ob_fin += $component['ob_fin'];
                $this->tot_exp_upto_phy += (int)$component['exp_upto_phy'];
                $this->tot_exp_upto_fin += (float)$component['exp_upto_fin'];
                $this->tot_exp_mon_phy += (int)$component['exp_mon_phy'];
                $this->tot_exp_mon_fin += (float)$component['exp_mon_fin'];
                $this->tot_exp_cum_phy += (int)$component['exp_cum_phy'];
                $this->tot_exp_cum_fin += (float)$component['exp_cum_fin'];
                $this->tot_fr_upto_phy += (int)$component['fr_upto_phy'];
                $this->tot_fr_upto_fin += (float)$component['fr_upto_fin'];
                $this->tot_fr_mon_phy += (int)$component['fr_mon_phy'];
                $this->tot_fr_mon_fin += (float)$component['fr_mon_fin'];
                $this->tot_fr_cum_phy += (int)$component['fr_cum_phy'];
                $this->tot_fr_cum_fin += (float)$component['fr_cum_fin'];
                $this->tot_cb_phy += $component['cb_phy'];
                $this->tot_cb_fin += $component['cb_fin'];

            }
            if (!empty($item['children'])){
                $html .= $this->generateTable($item['children'],$action);
                $html .= '<tr class="subtotal" data-parent="'.$item['component_id'].'">
                    <td colspan="2">Sub Total</td>
                    <td>'.$this->ob_phy.'</td>
                    <td>'.$this->ob_fin.'</td>
                    <td class="sub_upto_phy">'.$this->fr_upto_phy.'</td>
                    <td class="sub_upto_fin">'.in_lakh($this->fr_upto_fin).'</td>
                    <td class="sub_mon_phy">'.$this->fr_mon_phy.'</td>
                    <td class="sub_mon_fin">'.in_lakh($this->fr_mon_fin).'</td>
                    <td class="sub_cum_phy">'.$this->fr_cum_phy.'</td>
                    <td class="sub_cum_fin">'.in_lakh($this->fr_cum_fin).'</td>
                    <td class="sub_upto_phy">'.$this->exp_upto_phy.'</td>
                    <td class="sub_upto_fin">'.in_lakh($this->exp_upto_fin).'</td>
                    <td class="sub_mon_phy">'.$this->exp_mon_phy.'</td>
                    <td class="sub_mon_fin">'.in_lakh($this->exp_mon_fin).'</td>
                    <td class="sub_cum_phy">'.$this->exp_cum_phy.'</td>
                    <td class="sub_cum_fin">'.in_lakh($this->exp_cum_fin).'</td>
                    <td>'.$this->cb_phy.'</td>
                    <td>'.in_lakh($this->cb_fin).'</td>
                    </tr>
                ';
            }
        }

        return $html;

    }
}

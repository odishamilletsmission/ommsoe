<?php 
namespace Admin\Component\Controllers;

use Admin\Common\Models\CommonModel;
use Admin\Component\Models\AgencyComponentModel;
use Admin\Component\Models\BudgetModel;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use App\Controllers\AdminController;
use App\Traits\TreeTrait;
use Config\Url;

class Budget extends AdminController {

    use TreeTrait;
    public function index() {
        $bModel = new BlockModel();
        $agencyCompModel = new AgencyComponentModel();

        $districtsModel = new DistrictModel();
        $data['districts'] = $districtsModel->findAll();

        $data['district_id'] = '';
        $data['blocks'] = [];
        if($this->request->getGet('district_id')){
            $data['district_id'] = $this->request->getGet('district_id');
            $data['blocks'] = $bModel->where(['district_id'=>$this->request->getGet('district_id')])->findAll();
        }

        $data['block_id'] = '';
        if($this->request->getGet('block_id')){
            $data['block_id'] = $this->request->getGet('block_id');
        }
        $data['agency_type_id'] = '';
        if($this->request->getGet('agency_type_id')) {
            $data['agency_type_id'] = $this->request->getGet('agency_type_id');
        }

        $budgetModel = new BudgetModel();

        if($this->request->getMethod(1)=='POST'){
            $filter = [
                'block_id' => $data['block_id'],
                'district_id' => $data['district_id'],
                'agency_type_id' => $data['agency_type_id']
            ];
            $budgets = $budgetModel->where($filter)->findAll();
            if($budgets) {
                $budgetModel->where($filter)->delete();
            }
            $budget_data = [];
            foreach ($this->request->getPost('budget') as $component_id => $budget) {
                $budget_data[] = [
                    'block_id' => $data['block_id'],
                    'district_id' => $data['district_id'],
                    'agency_type_id' => $data['agency_type_id'],
                    'component_id' => $component_id,
                    'physical' => $budget['physical'],
                    'financial' => $budget['financial']
                ];
            }
            $budgetModel->insertBatch($budget_data);
            return redirect()->to(Url::componentBudget)->with('message','Budgets saved successfully.');
        }

        $filter = [
            'block_id' => (int)$data['block_id'],
            'district_id' => (int)$data['district_id'],
            'agency_type_id' => $data['agency_type_id']
        ];
        $filter = array_filter($filter);
        $components = [];
        if($filter) {
            $components = $budgetModel->getAgencyBudgets($filter);
        }

        $data['components'] = '';
        if($components) {
            $components = $this->buildTree($components);
            $data['components'] = $this->getTable($components);
        }

        $data['agency_types'] = (new CommonModel)->getAgencyTypes();

        return $this->template->view('Admin\Component\Views\budget', $data);
    }

    private function getTable($array) {
        $html = '';

        foreach ($array as $item) {
            if($item['row_type']=='heading') {
                $html .= '<tr>
                    <th>' . $item['number'] . '</th>
                    <th colspan="3">' . $item['description'] . '</th>
                ';
            } else {
                $html .= '<tr>
                    <td><label for="cb' . $item['id'] . '">' . $item['number'] . '</label></td>
                    <td><label for="cb' . $item['id'] . '">' . $item['description'] . '</label></td>
                    <td><input type="text" class="form-control" name="budget['.$item['id'].'][physical]" value="'.$item['physical'].'"></td>
                    <td><input type="text" class="form-control" name="budget['.$item['id'].'][financial]" value="'.$item['financial'].'"></td>
                    ';
            }
            if (!empty($item['children'])){
                $html .= $this->getTable($item['children']);
            }
            $html .= '</tr>';
        }

        return $html;

    }
}

<?php 
namespace Admin\Component\Controllers;
use Admin\Common\Models\CommonModel;
use Admin\Component\Models\AgencyComponentModel;
use Admin\Component\Models\ComponentModel;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use App\Controllers\AdminController;
use App\Traits\TreeTrait;

class District extends AdminController {

    use TreeTrait;
    public function index() {

        $districtsModel = new DistrictModel();
        $districts = $districtsModel->findAll();

        $data['comp_districts'] = [];
        foreach ($districts as $district) {

            $data['comp_districts'][] = [
                'id' => $district->id,
                'name' => $district->name,
                'has_component' => 'Yes',
                'assign_url' => admin_url('component/district/assign/'.$district->id),
            ];
        }

        return $this->template->view('Admin\Component\Views\district', $data);
    }

    public function assign($district_id) {
        $components = $this->getTreeArray();

        $dM = new DistrictModel();
        $aModel = new AgencyComponentModel();

        $district = $dM->find($district_id);

        $data['district_name'] = $district->name;
        $data['block_name'] = '';

        if($this->request->getMethod(1)=='POST'){
            $district_comps = [];
            foreach ($this->request->getPost('components') as $comp) {
                if(isset($comp['id'])){
                    $district_comps[] = [
                        'component_id' => $comp['id'],
                        'block_id' => 0,
                        'agency_type_id' => $comp['agency_type'],
                        'district_id' => $district->id,
                        'year' => '',
                    ];
                }
            }
            $aModel->where(['block_id'=>0,'district_id'=>$district_id])->delete();
            $aModel->insertBatch($district_comps);

            return redirect()->to(admin_url('component/district'));
        }

        $data['components'] = $aModel->getComponentsTable($components);

        $components = $aModel->where(['block_id'=>0,'district_id'=>$district_id])->findAll();
        $components_json = [];
        foreach ($components as $component) {
            $components_json[$component->component_id] = $component->agency_type_id;
        }
        $data['components_json'] = json_encode($components_json);

        return $this->template->view('Admin\Component\Views\assign', $data);
    }

}

<?php 
namespace Admin\Component\Controllers;

use Admin\Common\Models\CommonModel;
use Admin\Component\Models\AgencyComponentModel;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use Admin\Users\Models\UserGroupModel;
use App\Controllers\AdminController;
use App\Traits\TreeTrait;
use Config\Url;

class Agency extends AdminController {

    use TreeTrait;
    public function index() {
        $bModel = new BlockModel();
        $agencyCompModel = new AgencyComponentModel();

        $districtsModel = new DistrictModel();
        $data['districts'] = $districtsModel->findAll();

        $components = $this->getTreeArray();
        $data['components'] = $agencyCompModel->getComponentsTable($components);

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

        $filter = [
            'block_id' => $data['block_id'],
            'district_id' => $data['district_id'],
            'agency_type_id' => $data['agency_type_id']
        ];
        $filter = array_filter($filter);
        $components = [];
        if($filter) {
            $components = $agencyCompModel->getAgencyComponents($filter);
        }
        /*
        if($data['agency_type_id']==$this->settings->district_user){
            $district_id = $this->request->getGet('district_id');
            $components = $agencyCompModel->where([
                'block_id' => 0,
                'district_id' => $district_id
            ])->findAll();
        }

        if($data['agency_type_id']==$this->settings->block_user){
            $block_id = $this->request->getGet('block_id');
            $components = $agencyCompModel->where([
                'block_id' => $block_id,
            ])->findAll();
        }

        if($data['agency_type_id']==$this->settings->ps_user){

            $components = $agencyCompModel->where([
                'user_group' => $this->settings->ps_user,
            ])->findAll();
        }

        if($data['agency_type_id']==$this->settings->rs_user){

            $components = $agencyCompModel->where([
                'user_group' => $this->settings->rs_user,
            ])->findAll();
        }*/

        $components_json = [];

        if($components) {
            foreach ($components as $component) {
                $components_json[$component['id']] = $component['agency_type_id'];
            }
        }

        $data['components_json'] = json_encode($components_json);
        $data['agency_types'] = (new CommonModel)->getAgencyTypes();
        $data['assign_url'] = Url::componentAgencyAssign
            .'?agency_type_id='.$data['agency_type_id']
            .'&district_id='.$data['district_id']
            .'&block_id='.$data['block_id'];

        return $this->template->view('Admin\Component\Views\agency', $data);
    }

    public function assign() {

        $agencyCompModel = new AgencyComponentModel();

        $block_id = (int)$this->request->getGet('block_id');
        $district_id = (int)$this->request->getGet('district_id');
        $agency_type_id = (int)$this->request->getGet('agency_type_id');

        if($this->request->getMethod(1)=='POST'){
            $block_comps = [];
            foreach ($this->request->getPost('components') as $comp) {
                if(isset($comp['id'])){
                    $block_comps[] = [
                        'component_id' => $comp['id'],
                        'block_id' => $block_id,
                        'agency_type_id' => $comp['agency_type'],
                        'district_id' => $district_id,
                        'year' => '',
                        'user_group' => $agency_type_id,
                    ];

                    $agencyCompModel->where([
                        'component_id' => $comp['id'],
                        'block_id' => $block_id,
                        'agency_type_id' => $comp['agency_type'],
                        'district_id' => $district_id,
                        'user_group' => $agency_type_id,
                    ])->delete();

                }
            }
            $agencyCompModel->insertBatch($block_comps);

            return redirect()->to(Url::componentAgency);
        }

    }

}

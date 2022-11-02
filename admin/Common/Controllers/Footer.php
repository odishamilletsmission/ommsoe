<?php
namespace Admin\Common\Controllers;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use App\Controllers\AdminController;

class Footer extends AdminController
{
	public function index()
	{

        $data['show_old_portal'] = true;

        if($this->user->isLogged()) {

            $blockModel = new BlockModel();
            $districtModel = new DistrictModel();

            $new_blocks = $blockModel->asArray()->where(['year' => 6])->findAll();
            $new_dists = $districtModel->getNewDistricts();

            if($this->user->district_id && array_search($this->user->district_id, array_column($new_dists, 'district_id')) !== false) {
                $data['show_old_portal'] = false;
            }
            
            if($this->user->block_id && array_search($this->user->block_id, array_column($new_blocks, 'id')) !== false) {
                $data['show_old_portal'] = false;
            }
        }

		return view('Admin\Common\Views\footer',$data);
		
	}
}

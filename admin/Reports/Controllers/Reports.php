<?php 
namespace Admin\Reports\Controllers;
use Admin\Localisation\Models\DistrictModel;
use App\Controllers\AdminController;
use App\Traits\ReportTrait;

class Reports extends AdminController
{
    use ReportTrait;
    public function index() {
        $data = [];
        helper(['form']);
        return $this->template->view('Admin\Reports\Views\index', $data);
    }

    public function uploadStatus() {

        $data['districts'] = (new DistrictModel())->asArray()->findAll();
        $data['district_id'] = '';
        if($this->request->getGet('district_id')){
            $data['district_id'] = $this->request->getGet('district_id');
        }
        $data = array_merge($this->getUploadStatus($data),$data);

        return $this->template->view('Admin\Reports\Views\upload_status',$data);
    }

}

<?php
namespace Admin\Reports\Controllers;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use Admin\Reports\Models\MPRUploadModel;
use App\Controllers\AdminController;
use Config\Url;

class MprUpload extends AdminController
{
    public function index() {
        $this->template->add_package(['uploader','jquery_loading'],true);
        $filter = [];

        $data['year_id'] = getCurrentYearId();

        if($this->request->getGet('year')){
            $filter['year_id'] = $this->request->getGet('year');
            $data['year_id'] = $this->request->getGet('year');
        }

        $filter['district_id'] = $this->user->district_id;

        $filter['fund_agency_id'] = $this->user->fund_agency_id;

        $data['years'] = getAllYears();

        $months = (new MPRUploadModel())->getMPRUploads($filter);

        $data['months'] = [];
        foreach ($months as $month) {
            $filename = pathinfo($month->file,PATHINFO_BASENAME);
            $data['months'][] = [
                'month_id' => $month->month_id,
                'month' => $month->month,
                'date_uploaded' => $month->created_at ? ymdToDmy($month->created_at):'-',
                'status' => $month->file?'<label class="badge badge-success">Uploaded</label>':'-',
                'upload' => '<button class="btn btn-primary">Upload</button>',
                'file' => $month->file?'<a target="_blank" href="'.base_url($month->file).'">'.$filename.'</a>':'-',
            ];
        }
        $data['upload_url'] = Url::mprUpload.'?year='.$data['year_id'];

        return $this->template->view('Admin\Reports\Views\mpr_upload',$data);
    }

    public function upload() {
        $year = $this->request->getGet('year');
        $month = $this->request->getPost('month_id');

        $input = $this->validate([
            'file' => [
                'uploaded[file]',
                'max_size[file,60000000]',
                'ext_in[file,pdf]',
            ]
        ]);

        if (!$input) {
            $data = [
                'status' => false,
                'message' => 'Invalid file',
                'errors' => $this->validator->getErrors()
            ];
        } else {
            $file = $this->request->getFile('file');
            $file->move(DIR_UPLOAD . 'mpr');
            $data = [
                'status'=>true,
                'message'=> '<a target="_blank" href="'.base_url('uploads/mpr/'.$file->getName()).'">'.$file->getName().'</a>',
                'filename' => $file->getName(),
                'filepath' => $file->getName()
            ];

            $mpr = [
                'year' => $year,
                'month' => $month,
                'district_id' => $this->user->district_id,
                'fund_agency_id' => $this->user->fund_agency_id
            ];
            $mModel = new MPRUploadModel();
            $mpr_exists = $mModel->where($mpr)->first();
            $mpr['file'] = 'uploads/mpr/'.$file->getName();

            if($mpr_exists){
                $mModel->update($mpr_exists->id,$mpr);
                $mpr = $mModel->find($mpr_exists->id);
            } else {
                $id = $mModel->insert($mpr);
                $mpr = $mModel->find($id);
            }
            $data['date_uploaded'] = ymdToDmy($mpr->created_at);
            $data['uploaded'] = '<label class="badge badge-success">Uploaded</label>';
            $data['download'] = '<a target="_blank" href="'.base_url($mpr->file).'">'.$file->getName().'</a>';
        }
        return $this->response->setJSON($data);
    }
}
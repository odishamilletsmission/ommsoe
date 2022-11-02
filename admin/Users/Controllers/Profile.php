<?php
namespace Admin\Users\Controllers;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use Admin\Users\Models\UserModel;
use App\Controllers\AdminController;
use Config\Url;

class Profile extends AdminController{

    protected $error;
    public function index() {

        $this->template->add_package(['uploader','jquery_loading'],true);
        $userModel = new UserModel();
        $user = $userModel->find($this->user->user_id);

        if($this->request->getMethod(1)=='POST' && $this->validateForm()){
            $user->firstname = $this->request->getPost('firstname');
            $user->email = $this->request->getPost('email');
            $user->phone = $this->request->getPost('phone');
//            $user->district_id = $this->request->getPost('district_id');
//            $user->block_id = $this->request->getPost('block_id');
            $user->agency_name = $this->request->getPost('agency_name');

            if($this->request->getPost('password')) {
                $user->password = $this->request->getPost('password');
            }

            $userModel->save($user);

            return redirect()->to(Url::profile)->with('message','Profile information updated.');
        }

        $data['profile_image'] = 'https://raticupr.sirv.com/Images/no-image.jpg';
        if($user->image){
            $data['profile_image'] = site_url($user->image);
        }
        $data['agency_name'] = $user->agency_name;
        $data['firstname'] = $user->firstname;
        $data['email'] = $user->email;
        $data['phone'] = $user->phone;
        $data['district_id'] = $user->district_id;
        $data['block_id'] = $user->block_id;
        $data['district'] = '';
        if($user->district_id){
            $data['district'] = (new DistrictModel())->find($user->district_id)->name;
        }
        $data['block'] = '';
        if($user->block_id){
            $data['block'] = (new BlockModel())->find($user->block_id)->name;
        }

        $data['validation'] = \Config\Services::validation();

        $data['upload_url'] = site_url(Url::profileUpload);

        return $this->template->view('Admin\Users\Views\profile',$data);
    }

    public function upload() {

        $input = $this->validate([
            'file' => [
                'uploaded[file]',
                'mime_in[file,image/jpg,image/jpeg,image/png,image/JPG,image/JPEG,image/PNG]',
                'max_size[file,1024]',
                'ext_in[file,jpg,png,jpeg,JPG,PNG,JPEG]',
            ]
        ]);
        $filepath = 'images/profile/';

        if (!$input) {
            $data = [
                'status' => false,
                'message' => 'Invalid file',
                'errors' => $this->validator->getErrors()
            ];
        } else {
            $file = $this->request->getFile('file');
            $file->move($filepath);

            $data = [
                'status'=>true,
                'image'=> base_url($filepath.$file->getName()),
                'filename' => $file->getName(),
                'filepath' => $filepath.$file->getName()
            ];
            $userModel = new UserModel();
            $user = $userModel->find($this->user->user_id);
            $user->image = $filepath.$file->getName();
            $userModel->save($user);
        }
        return $this->response->setJSON($data);
    }

    protected function validateForm() {
        if($this->request->getPost('password')) {
            $rules = [
                "password" => [
                    "label" => "Password",
                    "rules" => "min_length[5]|max_length[20]"
                ],
                "password_confirm" => [
                    "label" => "Confirm Password",
                    "rules" => "matches[password]"
                ]
            ];

            if ($this->validate($rules)) {
                return true;
            } else {
                //printr($validation->getErrors());
                $this->error['warning'] = "Warning: Please check the form carefully for errors!";
                $this->session->setFlashdata('message', $this->error['warning']);
                return false;
            }
        }
        return !$this->error;
    }
}

/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */
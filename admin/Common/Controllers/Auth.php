<?php
namespace Admin\Common\Controllers;
use App\Controllers\AdminController;
use App\Libraries\User;
use CodeIgniter\HTTP\CURLRequest;

class Auth extends AdminController
{
	public function __construct()
    {
		$this->uri = service('uri');
	}
	public function login() {
        $data['login_error']='';

        if($this->user->isLogged()){
            return redirect()->to('/admin');
        }
        if($this->request->getMethod(1)=='POST'){
            $logged_in = $this->user->login(
                $this->request->getPost('username'),
                $this->request->getPost('password')
            );
            if($logged_in){
				if ($this->request->getPost('redirect') && (strpos($this->request->getPost('redirect'), admin_url()) == 0 )) {
					return redirect()->to($this->request->getPost('redirect'));
				} else{
					return redirect()->to(base_url('admin'));
				}
            } else {
                $data['login_error']=$this->session->getFlashdata('error');
            }
        }

		if($this->uri->getTotalSegments() > 0){
			$route=uri_string();
			$data['redirect'] = $route;
		} else {
			$data['redirect'] = '';
		}

		return $this->template->view('Admin\Common\Views\login',$data);
        //return view('common/login',$data);
	}

	public function logout() {
        $this->user->logout();
        $this->session->remove('redirect');
        return redirect()->to('/admin');
	}

	public function reLogin(){
        $user = $this->session->get('temp_user');
        $this->session->set('user',$user);
        $this->user->assignUserAttr($user);
        $this->session->remove('temp_user');
        return redirect()->to(base_url('admin'));
    }

    public function oldPortalLogin() {
        $client = \Config\Services::curlrequest();

        $response = $client->request('POST', 'https://soe1.milletsodisha.com/api/user/username', [
            'headers' => [
                'x-api-key' => "4o8c0ow0wooss4kswgwwcs4444swk0oc44gwc8gs",
            ],
            'form_params' => [
                'username' => $this->user->getUserName(),
//                'username' => 'fa_bijepur',
            ]
        ]);

        $body = $response->getBody();
        if (strpos($response->getHeader('content-type'), 'application/json') !== false) {
            $body = json_decode($body);
        }

        if(isset($body->status) && $body->status){
            $id = $body->user->id;
            $password = $body->user->password;
            return redirect()->to('https://soe1.milletsodisha.com/api/user/login/'.$id.'/'.$password.'?X-API-KEY=4o8c0ow0wooss4kswgwwcs4444swk0oc44gwc8gs');
        } else {
            return redirect()->with('message','Cannot login to old portal. Contact Admin')->to('/admin');
        }



    }
}

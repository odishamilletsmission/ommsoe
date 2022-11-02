<?php
namespace App\Controllers\Frontend\Common;
use App\Controllers\BaseController;
use App\Libraries\User;

class Auth extends BaseController
{
	public function login() {
        $data['login_error']='';
		
        if($this->user->isLogged()){
            return redirect()->to('/');
        }
        if($this->request->getMethod(1)=='POST'){
            $logged_in = $this->user->login(
                $this->request->getPost('username'),
                $this->request->getPost('password')
            );
            if($logged_in){

                $redirect = $this->session->get('redirect');

                if($redirect && !in_array($redirect,['login','logout'])){
                    $this->session->remove('redirect');
                    return redirect()->to($redirect);
                }
                return redirect()->to(base_url('/'));
            } else {
                $data['login_error']=$this->session->getFlashdata('error');
            }
        }
		return $this->template->view('common/login',$data);
        //return view('common/login',$data);
	}
	
	public function logout() {
        $this->user->logout();
        $this->session->remove('redirect');
        return redirect()->to('/');
	}
}

return  __NAMESPACE__ ."\Auth";
?>